/**
 * WordPress MCP Server for TorlyAI
 * 
 * This server enables Claude Code to interact with WordPress
 * installations, manage content, and perform administrative tasks.
 */

import { Server } from '@modelcontextprotocol/sdk/server/index.js';
import { StdioServerTransport } from '@modelcontextprotocol/sdk/server/stdio.js';
import {
  CallToolRequestSchema,
  ListToolsRequestSchema,
} from '@modelcontextprotocol/sdk/types.js';
import axios from 'axios';
import { exec } from 'child_process';
import { promisify } from 'util';
import dotenv from 'dotenv';

// Load environment variables
dotenv.config();

const execAsync = promisify(exec);

// Input validation and sanitization helpers
function sanitizeShellArg(arg) {
  // Remove dangerous characters and escape single quotes
  return arg.replace(/[;&|`$()\\<>]/g, '').replace(/'/g, "'\\''");
}

function validatePath(path) {
  // Only allow alphanumeric, forward slash, dash, underscore, and dot
  if (!/^[a-zA-Z0-9\/_.-]+$/.test(path)) {
    throw new Error('Invalid path format');
  }
  // Prevent directory traversal
  if (path.includes('..')) {
    throw new Error('Directory traversal not allowed');
  }
  return path;
}

function validateCommand(command) {
  // Whitelist of allowed WP-CLI commands
  const allowedCommands = [
    'post', 'page', 'plugin', 'theme', 'user', 'option', 'menu',
    'term', 'site', 'core', 'config', 'cache', 'rewrite'
  ];

  const baseCommand = command.trim().split(' ')[0];
  if (!allowedCommands.includes(baseCommand)) {
    throw new Error(`Command '${baseCommand}' is not allowed`);
  }

  // Check for command injection patterns
  if (/[;&|`$()\\<>]/.test(command)) {
    throw new Error('Command contains invalid characters');
  }

  return command;
}

// WordPress configuration
const WP_SITE_URL = process.env.WP_SITE_URL || 'https://torly.ai';
const WP_API_URL = `${WP_SITE_URL}/wp-json/wp/v2`;
const WP_CUSTOM_API_URL = `${WP_SITE_URL}/wp-json/torlyai/v1`;
const WP_USERNAME = process.env.WP_USERNAME;
const WP_APP_PASSWORD = process.env.WP_APP_PASSWORD;
const WP_CLI_PATH = process.env.WP_CLI_PATH || '/usr/local/bin/wp';

// GoDaddy API configuration
const GODADDY_API_KEY = process.env.GODADDY_API_KEY;
const GODADDY_API_SECRET = process.env.GODADDY_API_SECRET;
const GODADDY_API_URL = 'https://api.godaddy.com/v1';

// Create axios instance with authentication
const wpApi = axios.create({
  baseURL: WP_API_URL,
  auth: {
    username: WP_USERNAME,
    password: WP_APP_PASSWORD,
  },
  headers: {
    'Content-Type': 'application/json',
  },
});

// GoDaddy API instance
const godaddyApi = axios.create({
  baseURL: GODADDY_API_URL,
  headers: {
    'Authorization': `sso-key ${GODADDY_API_KEY}:${GODADDY_API_SECRET}`,
    'Content-Type': 'application/json',
  },
});

// Initialize MCP server
const server = new Server(
  {
    name: 'wordpress-torlyai-mcp',
    version: '1.0.0',
  },
  {
    capabilities: {
      tools: {},
    },
  }
);

// Tool definitions
const tools = [
  {
    name: 'wp_create_post',
    description: 'Create a new blog post in WordPress',
    inputSchema: {
      type: 'object',
      properties: {
        title: { type: 'string', description: 'Post title' },
        content: { type: 'string', description: 'Post content (HTML)' },
        excerpt: { type: 'string', description: 'Post excerpt' },
        status: { 
          type: 'string', 
          enum: ['draft', 'publish', 'pending'],
          description: 'Post status'
        },
        categories: {
          type: 'array',
          items: { type: 'number' },
          description: 'Category IDs'
        },
        tags: {
          type: 'array',
          items: { type: 'number' },
          description: 'Tag IDs'
        },
        featured_media: { type: 'number', description: 'Featured image ID' },
      },
      required: ['title', 'content'],
    },
  },
  {
    name: 'wp_update_post',
    description: 'Update an existing WordPress post',
    inputSchema: {
      type: 'object',
      properties: {
        id: { type: 'number', description: 'Post ID to update' },
        title: { type: 'string', description: 'New title' },
        content: { type: 'string', description: 'New content' },
        excerpt: { type: 'string', description: 'New excerpt' },
        status: { type: 'string', enum: ['draft', 'publish', 'pending'] },
      },
      required: ['id'],
    },
  },
  {
    name: 'wp_get_posts',
    description: 'Get list of WordPress posts',
    inputSchema: {
      type: 'object',
      properties: {
        per_page: { type: 'number', description: 'Number of posts to retrieve', default: 10 },
        page: { type: 'number', description: 'Page number', default: 1 },
        status: { type: 'string', description: 'Post status filter' },
        search: { type: 'string', description: 'Search term' },
      },
    },
  },
  {
    name: 'wp_delete_post',
    description: 'Delete a WordPress post',
    inputSchema: {
      type: 'object',
      properties: {
        id: { type: 'number', description: 'Post ID to delete' },
        force: { type: 'boolean', description: 'Force delete (skip trash)', default: false },
      },
      required: ['id'],
    },
  },
  {
    name: 'wp_create_page',
    description: 'Create a new page in WordPress',
    inputSchema: {
      type: 'object',
      properties: {
        title: { type: 'string', description: 'Page title' },
        content: { type: 'string', description: 'Page content (HTML)' },
        parent: { type: 'number', description: 'Parent page ID' },
        template: { type: 'string', description: 'Page template' },
        status: { type: 'string', enum: ['draft', 'publish', 'pending'] },
      },
      required: ['title', 'content'],
    },
  },
  {
    name: 'wp_upload_media',
    description: 'Upload media file to WordPress',
    inputSchema: {
      type: 'object',
      properties: {
        file_url: { type: 'string', description: 'URL of file to upload' },
        title: { type: 'string', description: 'Media title' },
        alt_text: { type: 'string', description: 'Alternative text' },
      },
      required: ['file_url'],
    },
  },
  {
    name: 'wp_cli_command',
    description: 'Execute WP-CLI command',
    inputSchema: {
      type: 'object',
      properties: {
        command: { type: 'string', description: 'WP-CLI command to execute' },
        path: { type: 'string', description: 'WordPress installation path' },
      },
      required: ['command'],
    },
  },
  {
    name: 'godaddy_update_dns',
    description: 'Update DNS records for torly.ai domain',
    inputSchema: {
      type: 'object',
      properties: {
        records: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              type: { type: 'string', enum: ['A', 'CNAME', 'MX', 'TXT', 'NS'] },
              name: { type: 'string', description: 'Record name (@ for root, or subdomain)' },
              data: { type: 'string', description: 'Record value' },
              ttl: { type: 'number', description: 'TTL in seconds', default: 3600 },
            },
            required: ['type', 'name', 'data'],
          },
        },
      },
      required: ['records'],
    },
  },
  {
    name: 'godaddy_get_dns',
    description: 'Get current DNS records for torly.ai',
    inputSchema: {
      type: 'object',
      properties: {
        type: { type: 'string', description: 'Filter by record type' },
        name: { type: 'string', description: 'Filter by record name' },
      },
    },
  },
  {
    name: 'wp_create_menu',
    description: 'Create or update WordPress navigation menu',
    inputSchema: {
      type: 'object',
      properties: {
        name: { type: 'string', description: 'Menu name' },
        items: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              title: { type: 'string' },
              url: { type: 'string' },
              parent: { type: 'number', description: 'Parent menu item ID' },
            },
            required: ['title', 'url'],
          },
        },
      },
      required: ['name', 'items'],
    },
  },
  {
    name: 'wp_install_plugin',
    description: 'Install and activate WordPress plugin',
    inputSchema: {
      type: 'object',
      properties: {
        slug: { type: 'string', description: 'Plugin slug from WordPress.org' },
        activate: { type: 'boolean', description: 'Activate after installation', default: true },
      },
      required: ['slug'],
    },
  },
  {
    name: 'wp_configure_multisite',
    description: 'Configure WordPress Multisite for blog.torly.ai',
    inputSchema: {
      type: 'object',
      properties: {
        subdomain_install: { type: 'boolean', description: 'Use subdomains (true) or subdirectories (false)', default: true },
        title: { type: 'string', description: 'Network title', default: 'TorlyAI Network' },
      },
    },
  },
  {
    name: 'torly_visa_assessment',
    description: 'Submit visa assessment data to TorlyAI custom API',
    inputSchema: {
      type: 'object',
      properties: {
        email: { type: 'string', description: 'User email' },
        business_name: { type: 'string' },
        innovation_factors: { type: 'array', items: { type: 'string' } },
        business_plan: { type: 'boolean' },
        growth_potential: { type: 'number', description: 'Growth potential percentage' },
        endorsing_body: { type: 'string', enum: ['UKES', 'Innovator International', 'Envestors'] },
      },
      required: ['email', 'business_name'],
    },
  },
  {
    name: 'oracle_deploy_wordpress',
    description: 'Deploy WordPress to Oracle Cloud VM via SSH',
    inputSchema: {
      type: 'object',
      properties: {
        vm_ip: { type: 'string', description: 'VM IP address' },
        ssh_key_path: { type: 'string', description: 'Path to SSH private key' },
        ssh_username: { type: 'string', description: 'SSH username', default: 'ubuntu' },
        deployment_script_path: { type: 'string', description: 'Path to deployment script' },
      },
      required: ['vm_ip', 'ssh_key_path'],
    },
  },
  {
    name: 'wp_create_blog_structure',
    description: 'Create blog categories, tags, and structure for blog.torly.ai',
    inputSchema: {
      type: 'object',
      properties: {
        categories: {
          type: 'array',
          items: { type: 'string' },
          description: 'Blog categories to create',
          default: ['UK Visa Guide', 'Innovator Visa', 'Business Immigration', 'Success Stories']
        },
        tags: {
          type: 'array',
          items: { type: 'string' },
          description: 'Blog tags to create',
          default: ['UK Immigration', 'Startup Visa', 'Business Plan', 'Endorsement']
        },
      },
    },
  },
  {
    name: 'wp_bulk_create_posts',
    description: 'Create multiple blog posts from JSON data',
    inputSchema: {
      type: 'object',
      properties: {
        posts: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              title: { type: 'string' },
              content: { type: 'string' },
              excerpt: { type: 'string' },
              categories: { type: 'array', items: { type: 'string' } },
              tags: { type: 'array', items: { type: 'string' } },
              featured_image_url: { type: 'string' },
              status: { type: 'string', enum: ['draft', 'publish'], default: 'publish' },
            },
            required: ['title', 'content'],
          },
        },
      },
      required: ['posts'],
    },
  },
  {
    name: 'verify_ssl_status',
    description: 'Verify SSL certificate installation and expiry',
    inputSchema: {
      type: 'object',
      properties: {
        domain: { type: 'string', description: 'Domain to check SSL certificate' },
      },
      required: ['domain'],
    },
  },
];

// Tool handlers
async function handleToolCall(name, args) {
  switch (name) {
    case 'wp_create_post':
      return await createPost(args);
    case 'wp_update_post':
      return await updatePost(args);
    case 'wp_get_posts':
      return await getPosts(args);
    case 'wp_delete_post':
      return await deletePost(args);
    case 'wp_create_page':
      return await createPage(args);
    case 'wp_upload_media':
      return await uploadMedia(args);
    case 'wp_cli_command':
      return await executeWpCli(args);
    case 'godaddy_update_dns':
      return await updateDnsRecords(args);
    case 'godaddy_get_dns':
      return await getDnsRecords(args);
    case 'wp_create_menu':
      return await createMenu(args);
    case 'wp_install_plugin':
      return await installPlugin(args);
    case 'wp_configure_multisite':
      return await configureMultisite(args);
    case 'torly_visa_assessment':
      return await submitVisaAssessment(args);
    case 'oracle_deploy_wordpress':
      return await deployToOracleCloud(args);
    case 'wp_create_blog_structure':
      return await createBlogStructure(args);
    case 'wp_bulk_create_posts':
      return await bulkCreatePosts(args);
    case 'verify_ssl_status':
      return await verifySSLStatus(args);
    default:
      throw new Error(`Unknown tool: ${name}`);
  }
}

// Tool implementation functions
async function createPost(args) {
  try {
    const response = await wpApi.post('/posts', args);
    return {
      success: true,
      post_id: response.data.id,
      link: response.data.link,
      message: `Post created successfully: ${response.data.link}`,
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function updatePost(args) {
  const { id, ...updateData } = args;
  try {
    const response = await wpApi.post(`/posts/${id}`, updateData);
    return {
      success: true,
      post_id: response.data.id,
      link: response.data.link,
      message: 'Post updated successfully',
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function getPosts(args) {
  try {
    const response = await wpApi.get('/posts', { params: args });
    return {
      success: true,
      posts: response.data.map(post => ({
        id: post.id,
        title: post.title.rendered,
        link: post.link,
        status: post.status,
        date: post.date,
      })),
      total: response.headers['x-wp-total'],
      pages: response.headers['x-wp-totalpages'],
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function deletePost(args) {
  try {
    const response = await wpApi.delete(`/posts/${args.id}`, {
      params: { force: args.force },
    });
    return {
      success: true,
      message: `Post ${args.id} deleted successfully`,
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function createPage(args) {
  try {
    const response = await wpApi.post('/pages', args);
    return {
      success: true,
      page_id: response.data.id,
      link: response.data.link,
      message: `Page created successfully: ${response.data.link}`,
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function uploadMedia(args) {
  try {
    // Download file from URL
    const fileResponse = await axios.get(args.file_url, {
      responseType: 'arraybuffer',
    });
    
    const formData = new FormData();
    formData.append('file', Buffer.from(fileResponse.data), {
      filename: args.file_url.split('/').pop(),
    });
    
    if (args.title) formData.append('title', args.title);
    if (args.alt_text) formData.append('alt_text', args.alt_text);
    
    const response = await wpApi.post('/media', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    
    return {
      success: true,
      media_id: response.data.id,
      url: response.data.source_url,
      message: 'Media uploaded successfully',
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function executeWpCli(args) {
  try {
    const path = validatePath(args.path || '/var/www/html');
    const command = validateCommand(args.command);

    const { stdout, stderr } = await execAsync(
      `${WP_CLI_PATH} ${command} --path=${path} --allow-root`
    );

    return {
      success: true,
      output: stdout,
      error: stderr,
    };
  } catch (error) {
    return {
      success: false,
      error: error.message,
    };
  }
}

async function updateDnsRecords(args) {
  try {
    const response = await godaddyApi.patch(
      `/domains/torly.ai/records`,
      args.records
    );
    
    return {
      success: true,
      message: 'DNS records updated successfully',
      records: args.records,
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function getDnsRecords(args) {
  try {
    const params = {};
    if (args.type) params.type = args.type;
    if (args.name) params.name = args.name;
    
    const response = await godaddyApi.get('/domains/torly.ai/records', { params });
    
    return {
      success: true,
      records: response.data,
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function createMenu(args) {
  try {
    const menuName = sanitizeShellArg(args.name);

    // This would typically use WP-CLI or custom API endpoint
    const { stdout } = await execAsync(
      `${WP_CLI_PATH} menu create '${menuName}' --allow-root`
    );

    // Add menu items
    for (const item of args.items) {
      const title = sanitizeShellArg(item.title);
      const url = sanitizeShellArg(item.url);
      await execAsync(
        `${WP_CLI_PATH} menu item add-custom '${menuName}' '${title}' '${url}' --allow-root`
      );
    }

    return {
      success: true,
      message: `Menu "${args.name}" created with ${args.items.length} items`,
    };
  } catch (error) {
    return {
      success: false,
      error: error.message,
    };
  }
}

async function installPlugin(args) {
  try {
    // Validate plugin slug (only alphanumeric, dash, underscore)
    if (!/^[a-z0-9-_]+$/.test(args.slug)) {
      throw new Error('Invalid plugin slug format');
    }

    const installCmd = `${WP_CLI_PATH} plugin install ${args.slug} --allow-root`;
    const { stdout: installOutput } = await execAsync(installCmd);

    if (args.activate) {
      const activateCmd = `${WP_CLI_PATH} plugin activate ${args.slug} --allow-root`;
      await execAsync(activateCmd);
    }

    return {
      success: true,
      message: `Plugin ${args.slug} installed${args.activate ? ' and activated' : ''}`,
      output: installOutput,
    };
  } catch (error) {
    return {
      success: false,
      error: error.message,
    };
  }
}

async function configureMultisite(args) {
  try {
    const title = sanitizeShellArg(args.title || 'TorlyAI Network');

    // Enable multisite in wp-config.php
    const enableCmd = `${WP_CLI_PATH} config set WP_ALLOW_MULTISITE true --raw --allow-root`;
    await execAsync(enableCmd);

    // Install multisite
    const installCmd = `${WP_CLI_PATH} core multisite-install --title='${title}' --${args.subdomain_install ? 'subdomains' : 'subdirectories'} --allow-root`;
    await execAsync(installCmd);

    // Create blog subdomain
    const createBlogCmd = `${WP_CLI_PATH} site create --slug=blog --title='TorlyAI Blog' --allow-root`;
    await execAsync(createBlogCmd);

    return {
      success: true,
      message: 'WordPress Multisite configured successfully',
      blog_url: 'https://blog.torly.ai',
    };
  } catch (error) {
    return {
      success: false,
      error: error.message,
    };
  }
}

async function submitVisaAssessment(args) {
  try {
    const response = await axios.post(`${WP_CUSTOM_API_URL}/visa-assessment`, args);

    return {
      success: true,
      score: response.data.score,
      recommendations: response.data.recommendations,
      message: 'Visa assessment submitted successfully',
    };
  } catch (error) {
    return {
      success: false,
      error: error.response?.data?.message || error.message,
    };
  }
}

async function deployToOracleCloud(args) {
  try {
    const { vm_ip, ssh_key_path, ssh_username = 'ubuntu', deployment_script_path } = args;

    // Validate inputs
    if (!/^[\d.]+$/.test(vm_ip)) {
      throw new Error('Invalid VM IP address format');
    }
    if (!/^[a-z_][a-z0-9_-]*$/.test(ssh_username)) {
      throw new Error('Invalid SSH username format');
    }

    const validatedKeyPath = validatePath(ssh_key_path);
    const scriptPath = deployment_script_path ? validatePath(deployment_script_path) : '/opt/torly-wordpress-setup/deployment/deploy-script.sh';

    // Copy deployment script to VM
    const { stdout: scpOutput } = await execAsync(
      `scp -i '${validatedKeyPath}' -o StrictHostKeyChecking=no '${scriptPath}' '${ssh_username}@${vm_ip}:/tmp/deploy-script.sh'`
    );

    // Execute deployment script on VM
    const { stdout: deployOutput } = await execAsync(
      `ssh -i '${validatedKeyPath}' -o StrictHostKeyChecking=no '${ssh_username}@${vm_ip}' "sudo bash /tmp/deploy-script.sh"`
    );

    return {
      success: true,
      message: 'WordPress deployed successfully to Oracle Cloud VM',
      vm_ip: vm_ip,
      output: deployOutput,
    };
  } catch (error) {
    return {
      success: false,
      error: error.message,
      details: error.stderr || error.stdout,
    };
  }
}

async function createBlogStructure(args) {
  try {
    const categories = args.categories || ['UK Visa Guide', 'Innovator Visa', 'Business Immigration', 'Success Stories'];
    const tags = args.tags || ['UK Immigration', 'Startup Visa', 'Business Plan', 'Endorsement'];

    const createdCategories = [];
    const createdTags = [];

    // Create categories
    for (const catName of categories) {
      const sanitizedName = sanitizeShellArg(catName);
      const { stdout } = await execAsync(
        `${WP_CLI_PATH} term create category '${sanitizedName}' --porcelain --allow-root`
      );
      createdCategories.push({ name: catName, id: parseInt(stdout.trim()) });
    }

    // Create tags
    for (const tagName of tags) {
      const sanitizedName = sanitizeShellArg(tagName);
      const { stdout } = await execAsync(
        `${WP_CLI_PATH} term create post_tag '${sanitizedName}' --porcelain --allow-root`
      );
      createdTags.push({ name: tagName, id: parseInt(stdout.trim()) });
    }

    return {
      success: true,
      message: 'Blog structure created successfully',
      categories: createdCategories,
      tags: createdTags,
    };
  } catch (error) {
    return {
      success: false,
      error: error.message,
    };
  }
}

async function bulkCreatePosts(args) {
  try {
    const { posts } = args;
    const createdPosts = [];

    for (const post of posts) {
      // Upload featured image if provided
      let featuredMediaId = null;
      if (post.featured_image_url) {
        const mediaResult = await uploadMedia({ file_url: post.featured_image_url });
        if (mediaResult.success) {
          featuredMediaId = mediaResult.media_id;
        }
      }

      // Get category IDs from names
      const categoryIds = [];
      if (post.categories && post.categories.length > 0) {
        for (const catName of post.categories) {
          try {
            const slug = sanitizeShellArg(catName.toLowerCase().replace(/\s+/g, '-'));
            const { stdout } = await execAsync(
              `${WP_CLI_PATH} term list category --slug='${slug}' --field=term_id --allow-root`
            );
            if (stdout.trim()) {
              categoryIds.push(parseInt(stdout.trim()));
            }
          } catch (e) {
            // Category not found, skip
          }
        }
      }

      // Get tag IDs from names
      const tagIds = [];
      if (post.tags && post.tags.length > 0) {
        for (const tagName of post.tags) {
          try {
            const slug = sanitizeShellArg(tagName.toLowerCase().replace(/\s+/g, '-'));
            const { stdout } = await execAsync(
              `${WP_CLI_PATH} term list post_tag --slug='${slug}' --field=term_id --allow-root`
            );
            if (stdout.trim()) {
              tagIds.push(parseInt(stdout.trim()));
            }
          } catch (e) {
            // Tag not found, skip
          }
        }
      }

      // Create the post
      const postData = {
        title: post.title,
        content: post.content,
        excerpt: post.excerpt || '',
        status: post.status || 'publish',
        categories: categoryIds,
        tags: tagIds,
      };

      if (featuredMediaId) {
        postData.featured_media = featuredMediaId;
      }

      const result = await createPost(postData);
      if (result.success) {
        createdPosts.push({
          title: post.title,
          post_id: result.post_id,
          link: result.link,
        });
      }
    }

    return {
      success: true,
      message: `${createdPosts.length} posts created successfully`,
      posts: createdPosts,
    };
  } catch (error) {
    return {
      success: false,
      error: error.message,
    };
  }
}

async function verifySSLStatus(args) {
  try {
    const { domain } = args;
    const tls = await import('tls');

    return new Promise((resolve) => {
      const socket = tls.connect({
        host: domain,
        port: 443,
        servername: domain,
        rejectUnauthorized: false,
      }, () => {
        const cert = socket.getPeerCertificate();
        const authorized = socket.authorized;

        const validFrom = new Date(cert.valid_from);
        const validTo = new Date(cert.valid_to);
        const now = new Date();
        const daysRemaining = Math.floor((validTo - now) / (1000 * 60 * 60 * 24));

        socket.end();

        resolve({
          success: true,
          domain: domain,
          certificate: {
            subject: cert.subject,
            issuer: cert.issuer,
            valid_from: cert.valid_from,
            valid_to: cert.valid_to,
            days_remaining: daysRemaining,
            authorized: authorized,
            protocol: socket.getProtocol(),
          },
          message: authorized ?
            `SSL certificate is valid (${daysRemaining} days remaining)` :
            `SSL certificate validation failed: ${socket.authorizationError}`,
        });
      });

      socket.on('error', (error) => {
        resolve({
          success: false,
          error: error.message,
          message: 'Failed to connect to SSL endpoint',
        });
      });
    });
  } catch (error) {
    return {
      success: false,
      error: error.message,
    };
  }
}

// Set up request handlers
server.setRequestHandler(ListToolsRequestSchema, async () => ({
  tools,
}));

server.setRequestHandler(CallToolRequestSchema, async (request) => {
  const { name, arguments: args } = request.params;
  
  try {
    const result = await handleToolCall(name, args);
    return {
      content: [
        {
          type: 'text',
          text: JSON.stringify(result, null, 2),
        },
      ],
    };
  } catch (error) {
    return {
      content: [
        {
          type: 'text',
          text: `Error: ${error.message}`,
        },
      ],
    };
  }
});

// Start the server
async function main() {
  const transport = new StdioServerTransport();
  await server.connect(transport);
  console.error('WordPress MCP Server for TorlyAI started');
}

main().catch((error) => {
  console.error('Server error:', error);
  process.exit(1);
});