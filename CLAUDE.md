# wp-freak

WordPress site for a Glasgow sports bar using Elementor 4 Pro. Active theme is **Raven** — a custom child theme of Hello Elementor.

## Key paths

| What | Where |
|---|---|
| Active theme | `wp-content/themes/raven/` |
| CSS source | `wp-content/themes/raven/assets/css/` |
| JS source | `wp-content/themes/raven/assets/js/` |
| Compiled output | `wp-content/themes/raven/assets/dist/` — **never edit directly** |
| Custom widgets | `wp-content/themes/raven/elementor/widgets/` |
| Theme functions | `wp-content/themes/raven/functions/` |
| Template parts | `wp-content/themes/raven/template-parts/` |

## Build

```bash
pnpm dev    # watch mode (runs from project root)
pnpm build  # production build
```

Tailwind 4 + Vite. Entry point is `assets/css/main.css`. Do not edit `assets/dist/`.

## Architecture

Elementor HTML output comes from three layers — edit the right layer for the task:

1. **Theme templates** (`header.php`, `footer.php`, `template-parts/`) — site-level wrappers outside the Elementor canvas. `template-parts/dynamic-header.php` and `template-parts/dynamic-footer.php` are rendered when an Elementor Pro Theme Builder header/footer is active.

2. **Custom Elementor widgets** (`elementor/widgets/*.php`) — PHP classes where `render()` defines the HTML. Any `.php` file in that directory is auto-registered. Never edit widget files inside the plugin itself.

3. **Theme Builder templates** — headers, footers, single/archive layouts built visually in Elementor > Templates > Theme Builder. Output is stored in the DB; edit via the Elementor editor, not PHP.

## CSS architecture

`main.css` imports in order:
```css
@import "tailwindcss/preflight";
@import "tailwindcss/theme";
@import "tailwindcss/utilities";
@import "./typography.css";
@import "./button.css";
@import "./homepage-hero.css";
```

`@source` directives point at `elementor/`, `functions/`, and `template-parts/` so Tailwind scans PHP files for class names.

### Design tokens (`@theme` in main.css)

**Colours** (mapped from Elementor global CSS vars):
- `--color-primary/secondary/text/accent` — from `--e-global-color-*`
- Custom palette: `birch`, `smoke`, `cloud`, `fire`, `ember`, `night`, `ship`, `pearl`

**Fonts:**
- `--font-heading`: `cubano-sharp, sans-serif`
- `--font-body`: `interstate, sans-serif`

**Spacing scale** — fluid clamp values, all named `--spacing-{size}`:

| Token | Class suffix | Range |
|---|---|---|
| `--spacing-4xs` | `*-4xs` | 4.5→5px |
| `--spacing-3xs` | `*-3xs` | 6.75→7.5px |
| `--spacing-2xs` | `*-2xs` | 9→10px |
| `--spacing-xs` | `*-xs` | 11→12.5px |
| `--spacing-sm` | `*-sm` | 13.5→15px |
| `--spacing-md` | `*-md` | 15.75→17.5px |
| `--spacing-lg` | `*-lg` | 18→20px |
| `--spacing-2xl` | `*-2xl` | 22.5→25px |
| `--spacing-3xl` | `*-3xl` | 27→30px |
| `--spacing-4xl` | `*-4xl` | 31.5→35px |
| `--spacing-5xl` | `*-5xl` | 36→40px |
| `--spacing-6xl` | `*-6xl` | 45→50px |
| `--spacing-7xl` | `*-7xl` | 54→60px |
| `--spacing-8xl` | `*-8xl` | 63→70px |
| `--spacing-9xl` | `*-9xl` | 72→80px |
| `--spacing-10xl` | `*-10xl` | 90→100px |
| `--spacing-11xl` | `*-11xl` | 108→120px |
| `--spacing-12xl` | `*-12xl` | 135→150px |

**Note:** There is no bare `xl` spacing — the scale jumps from `lg` to `2xl`. Old names (`2lg`, `3lg`, `4lg`, `xl`) have been removed.

### Typography utilities (`typography.css`)

Viewport range: 360px → 1240px

**Headings** (Cubano Sharp, weight 400):
- `text-heading-2xl` — 50→96px
- `text-heading-xl` — 46→76px
- `text-heading-lg` — 40→62px
- `text-heading-md` — 34→50px
- `text-heading-sm` — 28→40px
- `text-heading-xs` — 24→30px

**Subtitles** (Interstate, bold, uppercase, tracked):
- `text-subtitle-xl` — 20→22px
- `text-subtitle-lg` — 16→18px
- `text-subtitle-md` — 14→16px
- `text-subtitle-sm` — 14px fixed
- `text-subtitle-xs` — 12px fixed

**Body** (Interstate, regular):
- `text-body-xl` — 20→22px
- `text-body-lg` — 16→18px
- `text-body-md` — 14→16px
- `text-body-sm` — 14px fixed
- `text-body-xs` — 12px fixed

### Button utilities (`button.css`)

Classes: `btn`, `btn-primary`, `btn-secondary`, `btn-ghost`
Sizes: `btn-lg`, `btn-md`, `btn-sm`

## Custom widgets

All in `elementor/widgets/`. Traits live in `elementor/traits/`.

| Widget | File | Notes |
|---|---|---|
| Homepage Hero | `homepage-hero.php` | Slideshow/image/video bg, nav panel, Ken Burns, header hide-on-scroll |
| Masonry Cards | `masonry-cards.php` | Alternating 3-up grid with large/small cards |
| News List | `news-list.php` | Post grid with sticky post, category filter, events ordering |
| Two Column Media Text | `two-column-media-text.php` | Split image/video + text, switchable media position |
| Post Content | `post-content.php` | — |
| Post Title | `post-title.php` | — |
| Raven Heading | `raven-heading.php` | Heading with `{{fire colour}}` syntax |

**Shared traits** (`elementor/traits/`):
- `trait-padding.php` — `register_padding_controls()` / `get_padding_classes()`. Default padding: `10xl` (90–100px). Options: `none`, `md`, `lg`, `2xl`–`12xl`.
- `trait-button.php` — reusable button controls + `render_button()`. **Never construct buttons by hand-coding `btn btn-primary btn-md` class strings.** Inside an Elementor widget use the trait's `render_button()`. In template parts and other non-widget PHP use `raven_render_button( $label, $url, $variant, $size, $icon )` (defined in `functions/elementor.php`).

## Footer system

**Template:** `template-parts/dynamic-footer.php`
**Settings:** `functions/footer-options.php` — Settings > Footer in wp-admin

The footer has an optional CTA band (heading, body text, button) controllable globally and per-page:
- Global toggle: Settings > Footer > Enable CTA
- Per-page: sidebar meta box → inherit / always show / always hide

Options stored in: `raven_footer_cta_enabled`, `raven_footer_cta_heading`, `raven_footer_cta_text`, `raven_footer_cta_button_label`, `raven_footer_cta_button_url`

## Header behaviour

- Header element has class `js-freak-header`
- On pages where the homepage-hero widget is the **first** widget, the header starts hidden (`opacity: 0`) and fades in when the user scrolls to the next section
- CSS `:has()` hides the header at paint time to prevent flash: see `main.css` lines ~164–169
- JS scroll logic: `assets/js/homepage-hero.js`

## Functions

| File | Purpose |
|---|---|
| `functions/theme.php` | Asset enqueue, image sizes, nav menus, `nav_menu_link_attributes` filter for button-styled nav links |
| `functions/elementor.php` | Widget auto-registration, Elementor overrides |
| `functions/acf.php` | ACF field groups |
| `functions/footer-options.php` | Footer CTA settings page + per-page meta box |
