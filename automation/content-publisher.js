#!/usr/bin/env node

/**
 * Content Publisher for TorlyAI Blog
 *
 * This script publishes blog posts from blog-posts.json to WordPress
 * using the WordPress MCP server's bulk create posts functionality.
 *
 * Usage: node automation/content-publisher.js [--dry-run]
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import axios from 'axios';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Configuration
const CONFIG = {
  blogPostsFile: path.join(__dirname, '../content/blog-posts.json'),
  wpSiteUrl: process.env.WP_SITE_URL || 'https://torly.ai',
  wpUsername: process.env.WP_USERNAME,
  wpAppPassword: process.env.WP_APP_PASSWORD,
};

class ContentPublisher {
  constructor(dryRun = false) {
    this.dryRun = dryRun;
    this.wpApi = axios.create({
      baseURL: `${CONFIG.wpSiteUrl}/wp-json/wp/v2`,
      auth: {
        username: CONFIG.wpUsername,
        password: CONFIG.wpAppPassword,
      },
      headers: {
        'Content-Type': 'application/json',
      },
    });
  }

  /**
   * Load blog posts from JSON file
   */
  loadBlogPosts() {
    console.log(`📄 Loading blog posts from ${CONFIG.blogPostsFile}...`);

    if (!fs.existsSync(CONFIG.blogPostsFile)) {
      throw new Error(`Blog posts file not found: ${CONFIG.blogPostsFile}`);
    }

    const data = JSON.parse(fs.readFileSync(CONFIG.blogPostsFile, 'utf8'));
    console.log(`✅ Loaded ${data.posts.length} blog posts`);

    return data.posts;
  }

  /**
   * Get category ID by name (create if doesn't exist)
   */
  async getCategoryId(categoryName) {
    try {
      // Search for existing category
      const response = await this.wpApi.get('/categories', {
        params: { search: categoryName, per_page: 1 },
      });

      if (response.data.length > 0) {
        console.log(`  ✓ Found category: ${categoryName} (ID: ${response.data[0].id})`);
        return response.data[0].id;
      }

      // Create category if doesn't exist
      if (!this.dryRun) {
        const createResponse = await this.wpApi.post('/categories', {
          name: categoryName,
        });
        console.log(`  ✓ Created category: ${categoryName} (ID: ${createResponse.data.id})`);
        return createResponse.data.id;
      }

      console.log(`  ⚠ Would create category: ${categoryName}`);
      return null;
    } catch (error) {
      console.error(`  ❌ Error with category ${categoryName}:`, error.message);
      return null;
    }
  }

  /**
   * Get tag ID by name (create if doesn't exist)
   */
  async getTagId(tagName) {
    try {
      // Search for existing tag
      const response = await this.wpApi.get('/tags', {
        params: { search: tagName, per_page: 1 },
      });

      if (response.data.length > 0) {
        console.log(`  ✓ Found tag: ${tagName} (ID: ${response.data[0].id})`);
        return response.data[0].id;
      }

      // Create tag if doesn't exist
      if (!this.dryRun) {
        const createResponse = await this.wpApi.post('/tags', {
          name: tagName,
        });
        console.log(`  ✓ Created tag: ${tagName} (ID: ${createResponse.data.id})`);
        return createResponse.data.id;
      }

      console.log(`  ⚠ Would create tag: ${tagName}`);
      return null;
    } catch (error) {
      console.error(`  ❌ Error with tag ${tagName}:`, error.message);
      return null;
    }
  }

  /**
   * Check if post already exists by title
   */
  async postExists(title) {
    try {
      const response = await this.wpApi.get('/posts', {
        params: { search: title, per_page: 1 },
      });

      return response.data.length > 0;
    } catch (error) {
      return false;
    }
  }

  /**
   * Publish a single blog post
   */
  async publishPost(post) {
    console.log(`\n📝 Processing: "${post.title}"`);

    // Check if post already exists
    const exists = await this.postExists(post.title);
    if (exists) {
      console.log(`  ⏭  Post already exists, skipping`);
      return { skipped: true, title: post.title };
    }

    // Get category IDs
    const categoryIds = [];
    if (post.categories && post.categories.length > 0) {
      console.log(`  📁 Processing ${post.categories.length} categories...`);
      for (const categoryName of post.categories) {
        const id = await this.getCategoryId(categoryName);
        if (id) categoryIds.push(id);
      }
    }

    // Get tag IDs
    const tagIds = [];
    if (post.tags && post.tags.length > 0) {
      console.log(`  🏷  Processing ${post.tags.length} tags...`);
      for (const tagName of post.tags) {
        const id = await this.getTagId(tagName);
        if (id) tagIds.push(id);
      }
    }

    if (this.dryRun) {
      console.log(`  ⚠ DRY RUN: Would create post with:`);
      console.log(`    Title: ${post.title}`);
      console.log(`    Categories: ${categoryIds.join(', ')}`);
      console.log(`    Tags: ${tagIds.join(', ')}`);
      console.log(`    Status: ${post.status || 'publish'}`);
      return { dryRun: true, title: post.title };
    }

    // Create the post
    try {
      console.log(`  📤 Creating post...`);

      const postData = {
        title: post.title,
        content: post.content,
        excerpt: post.excerpt || '',
        status: post.status || 'publish',
        categories: categoryIds,
        tags: tagIds,
      };

      const response = await this.wpApi.post('/posts', postData);

      console.log(`  ✅ Post created successfully!`);
      console.log(`    ID: ${response.data.id}`);
      console.log(`    URL: ${response.data.link}`);

      return {
        success: true,
        title: post.title,
        id: response.data.id,
        link: response.data.link,
      };
    } catch (error) {
      console.error(`  ❌ Failed to create post:`, error.response?.data?.message || error.message);
      return {
        error: true,
        title: post.title,
        message: error.response?.data?.message || error.message,
      };
    }
  }

  /**
   * Publish all blog posts
   */
  async publishAll() {
    console.log('🚀 Starting content publication...\n');

    if (this.dryRun) {
      console.log('⚠️  DRY RUN MODE - No actual changes will be made\n');
    }

    // Validate credentials
    if (!CONFIG.wpUsername || !CONFIG.wpAppPassword) {
      throw new Error('WordPress credentials not configured. Set WP_USERNAME and WP_APP_PASSWORD environment variables.');
    }

    // Load blog posts
    const posts = this.loadBlogPosts();

    // Publish each post
    const results = [];
    for (const post of posts) {
      const result = await this.publishPost(post);
      results.push(result);

      // Add delay to avoid rate limiting
      await new Promise(resolve => setTimeout(resolve, 1000));
    }

    // Print summary
    console.log('\n' + '='.repeat(60));
    console.log('📊 PUBLICATION SUMMARY');
    console.log('='.repeat(60));

    const successful = results.filter(r => r.success).length;
    const skipped = results.filter(r => r.skipped).length;
    const errors = results.filter(r => r.error).length;
    const dryRun = results.filter(r => r.dryRun).length;

    console.log(`Total posts processed: ${results.length}`);
    if (this.dryRun) {
      console.log(`Would create: ${dryRun}`);
    } else {
      console.log(`Successfully created: ${successful}`);
      console.log(`Skipped (already exist): ${skipped}`);
      console.log(`Errors: ${errors}`);
    }

    if (successful > 0) {
      console.log('\n📝 Created posts:');
      results.filter(r => r.success).forEach(r => {
        console.log(`  - ${r.title}`);
        console.log(`    ${r.link}`);
      });
    }

    if (errors > 0) {
      console.log('\n❌ Failed posts:');
      results.filter(r => r.error).forEach(r => {
        console.log(`  - ${r.title}: ${r.message}`);
      });
    }

    console.log('\n✅ Content publication complete!');

    return results;
  }
}

// Run if called directly
if (import.meta.url === `file://${process.argv[1]}`) {
  const dryRun = process.argv.includes('--dry-run');

  const publisher = new ContentPublisher(dryRun);
  publisher.publishAll()
    .then((results) => {
      const hasErrors = results.some(r => r.error);
      process.exit(hasErrors ? 1 : 0);
    })
    .catch((error) => {
      console.error('\n❌ Publication failed:', error.message);
      process.exit(1);
    });
}

export default ContentPublisher;
