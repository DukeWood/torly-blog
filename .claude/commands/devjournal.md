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

## IMPORTANT: When Adding New Entries

**CRITICAL RULES - MUST FOLLOW:**
1. **ALWAYS APPEND** - Never overwrite or delete existing entries
2. **READ FIRST** - Always use the Read tool to get the current file contents before editing
3. **PRESERVE ALL** - Keep 100% of previous records unchanged
4. **ADD AT BOTTOM** - Insert new date entries at the end of the file (chronological order)
5. **USE EDIT TOOL** - Use the Edit tool to append, never Write tool (Write overwrites entire file)
6. **MAINTAIN FORMAT** - Follow the exact markdown structure of existing entries

**Append Process:**
1. Read the entire `dev_journal.md` file
2. Identify the last line of content (e.g., `*Last Updated: November 17, 2025, 23:00 GMT*`)
3. Use Edit tool to add new entry AFTER the last line
4. Keep the file header unchanged: `# Development Journal - Torly AI WordPress Setup`

**New Entry Template Structure:**
```markdown
---

## [DATE - e.g., November 18, 2025]

### Summary
[1-2 sentence overview of work completed]

---

## Tasks Completed

### 1. [Task Category Name] ✅

**Objective**: [What you were trying to achieve]

**Actions Taken**:
- [Bullet point list of actions]
- [Include commands, file changes, etc.]

**Files Modified**:
- [List of files with paths]

---

### 2. [Another Task Category] ✅
[...same structure...]

---

## Final Verification
[List of verification steps and results]

---

## Key Learnings
[Important insights from this work session]

---

## Statistics
- **Metric 1**: Value
- **Metric 2**: Value
- **Time Spent**: ~X hours

---

## Next Steps (Optional)
[Future improvements or follow-up tasks]

---

*Last Updated: [DATE], [TIME] GMT*
```

**Safety Check Before Appending:**
- Confirm the last entry date in the file
- Show the user the new entry date you're about to add
- Verify no duplicate dates will be created
- Use Edit tool with old_string = last few lines, new_string = last few lines + new entry
