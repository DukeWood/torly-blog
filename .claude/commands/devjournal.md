# Dev Journal Command

Read the development journal file at `dev_journal.md` and provide a comprehensive summary of:

1. **Latest Entry Date**: Show the most recent work session date
2. **All Important Tasks Completed**: List ALL significant tasks from the latest entry, grouped by category (Migration, Configuration, Bug Fixes, etc.)
3. **Key Git Commits**: Show recent git commits related to the work
   - Run `git log --oneline -10` to show last 10 commits
   - Highlight commits related to current work
4. **Critical Changes**: Highlight the most important modifications made
5. **Files Modified**: Complete list of all files changed in latest entry
6. **Commands Used**: Key WP-CLI, bash, or SQL commands executed
7. **Current Status**: Show overall project status with checkmarks

Then ask the user if they want to:
- View full journal entries
- Add a new entry for today's work
- Search for specific topics/dates
- View project statistics
- Create a git commit with today's changes

If the user wants to add a new entry, provide a template following the existing format with sections for:
- Date & Time
- Summary (1-2 sentences)
- Tasks Completed (categorized)
- Actions Taken (detailed steps)
- Files Modified (with file paths)
- Git Commits (if any)
- Verification & Testing
- Key Learnings
- Next Steps (optional improvements)
- Statistics

Always maintain the same markdown format and structure as the existing journal entries.
