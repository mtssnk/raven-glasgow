# wp-freak

WordPress site using Elementor 4 Pro. The active theme is a custom child theme of Hello Elementor.

## Key paths

| What | Where |
|---|---|
| Active theme | `wp-content/themes/raven/` |
| Parent theme | `wp-content/themes/hello-elementor/` |
| Elementor plugin | `wp-content/plugins/elementor/` |
| Elementor Pro plugin | `wp-content/plugins/elementor-pro/` |
| Custom widgets | `wp-content/themes/raven/elementor/widgets/` |

## Architecture

Elementor HTML output comes from three layers — edit the right layer for the task:

1. **Theme templates** (`header.php`, `footer.php`, `template-parts/`) — site-level
   wrappers outside the Elementor canvas. Override by copying parent files into the
   child theme at the same relative path.

2. **Custom Elementor widgets** (`elementor/widgets/*.php`) — PHP classes where
   `render()` defines the widget's HTML. Any `.php` file dropped in that directory is
   auto-registered. Never edit widget files inside the plugin itself.

3. **Theme Builder templates** — headers, footers, single/archive/404 layouts built
   visually in Elementor > Templates > Theme Builder. Output is stored in the DB;
   control markup via the Elementor editor, not PHP files.

## Child theme README

Full developer reference including the widget pattern, asset enqueuing, and hook
examples is in the child theme:
`wp-content/themes/raven/README.md`
