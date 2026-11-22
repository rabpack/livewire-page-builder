# livewire-page-builder


## Widget library

21 widgets ship by default: container, grid, heading, text, image slider, course/product slider, reviews, form, login gate, breadcrumb, gallery, button, image, divider, spacer, alert, accordion, tabs, pricing table, progress bar and counter.

Every image-based widget uses the host application's file manager. The gallery reads real catalog items from configuration, so pages stay in sync with the store.

The editor provides Elementor-style grouped tools, content/style/advanced settings, page settings, responsive visibility, undo/redo, drag reordering, nested containers/grids and light/dark themes.


## Editing workflow

The builder includes three ready-made page templates, per-block copy/paste, JSON import/export, undo/redo, responsive visibility and a sanitized CKEditor field for rich text. Rich text is passed through the host application's configured HTML sanitizer before rendering.

Form submissions are stored in `page_builder_form_submissions` and can be reviewed at `/page-builder/submissions` when the host application configures the submissions component and permission.
