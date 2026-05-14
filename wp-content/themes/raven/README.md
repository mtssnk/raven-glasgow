# Hello Elementor Child Theme

Child theme of [Hello Elementor](../hello-elementor/) for custom Elementor widget development.

## Structure

```
hello-elementor-child/
├── style.css                     ← WordPress theme declaration
├── functions.php                 ← Style enqueue + widget auto-registration
├── assets/
│   ├── css/                      ← Custom stylesheets
│   └── js/                       ← Custom scripts
└── elementor/
    └── widgets/                  ← Custom Elementor widget classes
        └── example-widget.php    ← Starter widget (copy this to create new ones)
```

## Where Elementor HTML comes from

There are three distinct layers:

### 1. Theme shell (this theme)
Standard WordPress template files that wrap the Elementor canvas. Edit these for
anything outside the Elementor editor — site-level wrappers, `<head>` tags, etc.
Override a parent template by copying it into this directory at the same path.

- `header.php` / `footer.php` — outermost HTML shell
- `template-parts/header.php` — rendered when no Elementor header template is active
- `template-parts/dynamic-header.php` / `dynamic-footer.php` — rendered when an
  Elementor Pro Theme Builder header/footer template is active

### 2. Elementor widget HTML
Each widget's HTML is defined in its PHP `render()` method. Core widget files live
inside the Elementor plugin and must not be edited (they'll be overwritten on update).

**To customise widget output, use one of these approaches:**

**A) Create a custom widget** — best for net-new components.
Drop a PHP file in `elementor/widgets/`. It will be auto-registered. See
`elementor/widgets/example-widget.php` for the full pattern.

**B) Hook into existing widget output** — best for minor modifications.
```php
// Before/after a specific widget renders
add_action( 'elementor/widget/{widget-name}/before_render', function( $widget ) { ... } );
add_action( 'elementor/widget/{widget-name}/after_render',  function( $widget ) { ... } );

// Filter the rendered HTML of any widget
add_filter( 'elementor/widget/render_content', function( $content, $widget ) { ... }, 10, 2 );
```

### 3. Elementor Theme Builder templates
Headers, footers, single post layouts, archive pages, and the 404 page can be built
visually in **Elementor > Templates > Theme Builder**. Their HTML is stored in the
database and rendered by the plugin — there is no PHP file to edit. Control the markup
by choosing and configuring widgets in the Elementor editor.

## Adding a custom widget

1. Copy `elementor/widgets/example-widget.php` to a new file in the same directory.
2. Rename the class (e.g. `Hello_Child_My_Widget`) and update `get_name()`,
   `get_title()`, and `get_icon()`.
3. Add your controls in `register_controls()` and your HTML in `render()`.
4. The widget appears automatically in the Elementor panel under the category
   returned by `get_categories()` (default: `general`).

## Enqueuing assets

Add stylesheets and scripts in `functions.php`:

```php
function hello_child_enqueue_scripts() {
    wp_enqueue_style(
        'hello-child-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'hello_child_enqueue_scripts' );
```

To enqueue assets only inside the Elementor editor:

```php
add_action( 'elementor/editor/after_enqueue_scripts', function() {
    wp_enqueue_script( 'hello-child-editor', get_stylesheet_directory_uri() . '/assets/js/editor.js' );
} );
```
