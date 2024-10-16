
# Overview  of this plugin

1. **Custom Post Type (Books)**: It registers a new post type called "Books" with support for the WordPress REST API and Gutenberg editor.
2. **Shortcode to Display Books**: It registers a shortcode `[display_books]` that displays a list of "Books" posts on any page or post.
3. **Custom REST API Endpoint**: It creates a custom REST API endpoint (`/wp-json/custom/v1/books`) that returns a list of "Books" in JSON format.

### Here’s a README file for this plugin:

---

# Custom Post Type and API Plugin

**Version**: 1.0  
**Author**: Your Name  
**Description**: A simple plugin to create a custom post type "Books", display it via a shortcode, and expose it through a REST API endpoint.

---

## Features

1. **Custom Post Type - "Books"**:
    - Creates a new post type called "Books" in the WordPress admin.
    - Supports the title, editor, and thumbnail.
    - Fully compatible with Gutenberg and the WordPress REST API.

2. **Shortcode to Display Books**:
    - The plugin includes a shortcode `[display_books]` that can be used to display a list of "Books" on any page or post.
    - You can specify the number of posts to display using the `posts_per_page` attribute, like so:  
      `[display_books posts_per_page="10"]`

3. **Custom REST API Endpoint**:
    - Exposes a custom REST API endpoint:  
      `/wp-json/custom/v1/books`
    - Returns a JSON array of the latest 10 "Books", including the title, content, and a link to each book.

---

## Installation

1. **Upload the Plugin**:
    - Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin via the WordPress Plugin Repository (if published there).

2. **Activate the Plugin**:
    - Go to the **Plugins** menu in WordPress and activate the "Custom Post Type and API Plugin".

---

## Usage

### 1. **Adding Books**:
   - After activation, you will see a new menu called "Books" in the WordPress admin sidebar.
   - You can add new books by navigating to **Books > Add New**.

### 2. **Using the Shortcode**:
   - To display a list of books, use the shortcode `[display_books]` in any post, page, or widget.
   - You can optionally specify the number of books to display:  
     `[display_books posts_per_page="5"]`

### 3. **Accessing the REST API**:
   - You can fetch a list of "Books" in JSON format via this URL:
     ```
     /wp-json/custom/v1/books
     ```
   - This endpoint will return the latest 10 books by default, with each entry containing:
     - **title**: The book title.
     - **content**: The book content.
     - **link**: A link to the book.

---

## Code Breakdown

1. **Custom Post Type**:
   - The `create_custom_post_type()` function registers the custom post type "Books", making it available in the admin interface with support for REST API and Gutenberg.

2. **Shortcode**:
   - The `display_books_shortcode()` function is responsible for querying and displaying books on the frontend using a shortcode. It outputs a list of books with links to each one.

3. **REST API**:
   - The custom REST API endpoint is created with `register_rest_route()`, and the data is fetched using the `get_books_api()` function, which returns the latest 10 books in JSON format.

---

## License
This plugin is released under the GPLv2 license.

---

## Example API Response

A sample response from the `/wp-json/custom/v1/books` API endpoint:

```json
[
  {
    "title": "Book Title 1",
    "content": "This is the content of Book 1.",
    "link": "https://your-site.com/book/book-title-1/"
  },
  {
    "title": "Book Title 2",
    "content": "This is the content of Book 2.",
    "link": "https://your-site.com/book/book-title-2/"
  }
]
```

---

## Changelog

### Version 1.0
- Initial release.
- Custom post type "Books" created.
- Shortcode for displaying books added.
- REST API endpoint for fetching books added.

---
