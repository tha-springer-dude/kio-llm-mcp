# KIO LLM MCP

**Custom Model Context Protocol (MCP) integration for WordPress.**

KIO LLM MCP extends WordPress with custom abilities that allow compatible AI assistants and agents to interact with WordPress content through the MCP ecosystem.

The project focuses on making WordPress functionality accessible to AI agents through structured, permission-controlled operations.

## Features

### Post Management
- List WordPress posts by status.
- Find posts by ID or title.
- Search posts by category.
- Create new posts.
- Update existing posts.
- Delete posts.

### Category Management
- List categories.
- Find categories by ID.
- Find categories by name.

### Media Management
- Search images in the WordPress Media Library.
- Retrieve image information, including URLs, titles, alt text and captions.

### MCP Integration
- Registers custom WordPress abilities.
- Exposes selected abilities through MCP.
- Includes OAuth trusted-publisher configuration for ChatGPT.

## Technical Overview

- **Platform:** WordPress
- **Language:** PHP
- **Integration:** Model Context Protocol (MCP)
- **Architecture:** WordPress Abilities API
- **Authentication:** OAuth integration

## Project Structure

```text
kio-llm-mcp/
├── custom_mcp_functions/
│   ├── categories/
│   ├── posts/
│   └── search-media.php
├── mcp-assets/
│   └── kio-mcp-publisher.php
└── kio-llm-mcp.php
```

## Permissions and Security

Each ability defines its own permission callback using WordPress capability checks.

Examples include:
- `read` for content retrieval.
- `edit_posts` for creating posts.

This ensures that operations are subject to the permissions of the authenticated WordPress user.

**Important:** This project is intended for controlled WordPress environments. Review permissions and authentication settings before deploying it on a production website.

## Status

Version 1.0.0

An ongoing development project exploring practical integration between WordPress and AI-powered agents.

## Author

Willie Springer  
[GitHub Profile](https://github.com/tha-springer-dude)
