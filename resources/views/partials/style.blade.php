<style data-page-builder-style>
.pb-block{--pb-font:{!! app(\Elearnac\PageBuilder\Services\FontRegistry::class)->cssStack() !!};--pb-color:#334155;--pb-color-dark:#e2e8f0;--pb-bg:#ffffff;--pb-bg-dark:#0f172a;--pb-border:#e2e8f0;--pb-accent:#18b7a5;--pb-accent-dark:#22d3ee;--pb-radius:12px;--pb-padding:16px;--pb-gap:16px;--pb-size:16px;--pb-line-height:1.6;--pb-letter-spacing:0;--pb-weight:400;--pb-align:right;font-family:var(--pb-font);color:var(--pb-color);text-align:var(--pb-align)}
html.dark .pb-block{color:var(--pb-color-dark)}
.pb-page{--pb-font:{!! app(\Elearnac\PageBuilder\Services\FontRegistry::class)->cssStack() !!};background:var(--pb-bg,#f8fafc);color:var(--pb-color,#0f172a);font-family:var(--pb-font);max-width:var(--pb-container,1200px);margin-inline:auto}
html.dark .pb-page,html.dark [data-page-builder-preview].pb-page{background:var(--pb-bg-dark,#020617)!important;color:var(--pb-color-dark,#e2e8f0)!important}
.pb-card{background:var(--pb-bg);border:1px solid var(--pb-border);border-radius:var(--pb-radius);padding:var(--pb-padding);color:inherit}
html.dark .pb-card{background:var(--pb-bg-dark);border-color:#334155;color:var(--pb-color-dark)}
.pb-title{font-family:var(--pb-font);font-size:var(--pb-size);font-weight:var(--pb-weight);line-height:var(--pb-line-height);letter-spacing:var(--pb-letter-spacing);color:var(--pb-color)}
html.dark .pb-title{color:var(--pb-color-dark)}
.pb-muted{color:#64748b}
html.dark .pb-muted{color:#94a3b8}
.pb-link,.pb-button{background:var(--pb-accent);border:0;border-radius:var(--pb-radius);color:#fff;cursor:pointer;display:inline-flex;gap:.5rem;align-items:center;justify-content:center;font-family:var(--pb-font);font-weight:700;padding:.65rem 1.1rem;text-decoration:none;transition:filter .2s ease,transform .2s ease}
.pb-link:hover,.pb-button:hover{filter:brightness(.94)}
.pb-button-outline{background:transparent;border:1px solid var(--pb-accent);color:var(--pb-accent)}
.pb-button-ghost{background:transparent;color:var(--pb-accent)}
html.dark .pb-button{background:var(--pb-accent-dark);color:#04121c}
.pb-chip{display:inline-flex;align-items:center;gap:.25rem;border-radius:999px;background:rgba(24,183,165,.12);color:#0f766e;font-size:.7rem;font-weight:700;padding:.2rem .55rem}
html.dark .pb-chip{background:rgba(34,211,238,.14);color:#67e8f9}
.pb-layout{display:flex;flex-direction:column;min-height:var(--pb-min-height,0);justify-content:var(--pb-justify,flex-start);align-items:var(--pb-items,stretch);gap:var(--pb-gap,16px)}
.pb-layout > .pb-grid{flex:1}
.pb-grid{display:grid;grid-template-columns:repeat(var(--pb-columns,12),minmax(0,1fr));gap:var(--pb-gap)}
@media(min-width:1600px){.pb-grid{grid-template-columns:repeat(var(--pb-columns-wide,var(--pb-columns,12)),minmax(0,1fr))}}
@media(max-width:1024px){.pb-grid{grid-template-columns:repeat(var(--pb-columns-tablet,var(--pb-columns,12)),minmax(0,1fr))}}
@media(max-width:640px){.pb-grid{grid-template-columns:repeat(var(--pb-columns-mobile,var(--pb-columns,1)),minmax(0,1fr))}}
.pb-grid-child{min-width:0;grid-column:var(--pb-offset,auto) / span var(--pb-span,12);order:var(--pb-order,0)}
@media(min-width:1600px){.pb-grid-child{grid-column:var(--pb-offset-wide,var(--pb-offset,auto)) / span var(--pb-span-wide,var(--pb-span,12));order:var(--pb-order-wide,var(--pb-order,0))}}
@media(max-width:1024px){.pb-grid-child{grid-column:var(--pb-offset-tablet,var(--pb-offset,auto)) / span var(--pb-span-tablet,var(--pb-span,12));order:var(--pb-order-tablet,var(--pb-order,0))}}
@media(max-width:640px){.pb-grid-child{grid-column:var(--pb-offset-mobile,var(--pb-offset,auto)) / span var(--pb-span-mobile,var(--pb-span,12));order:var(--pb-order-mobile,var(--pb-order,0))}}
.pb-device-wide .pb-grid{grid-template-columns:repeat(var(--pb-columns-wide,var(--pb-columns,12)),minmax(0,1fr))}
.pb-device-tablet .pb-grid{grid-template-columns:repeat(var(--pb-columns-tablet,var(--pb-columns,12)),minmax(0,1fr))}
.pb-device-mobile .pb-grid{grid-template-columns:repeat(var(--pb-columns-mobile,var(--pb-columns,1)),minmax(0,1fr))}
.pb-device-wide .pb-grid-child{grid-column:var(--pb-offset-wide,var(--pb-offset,auto)) / span var(--pb-span-wide,var(--pb-span,12));order:var(--pb-order-wide,var(--pb-order,0))}
.pb-device-tablet .pb-grid-child{grid-column:var(--pb-offset-tablet,var(--pb-offset,auto)) / span var(--pb-span-tablet,var(--pb-span,12));order:var(--pb-order-tablet,var(--pb-order,0))}
.pb-device-mobile .pb-grid-child{grid-column:var(--pb-offset-mobile,var(--pb-offset,auto)) / span var(--pb-span-mobile,var(--pb-span,12));order:var(--pb-order-mobile,var(--pb-order,0))}
.pb-input{width:100%;border:1px solid var(--pb-border);border-radius:calc(var(--pb-radius) / 2);padding:.55rem .7rem;background:#fff;color:#0f172a;font-family:var(--pb-font);font-size:.8rem}
html.dark .pb-input{background:#0f172a;border-color:#334155;color:#e2e8f0}
.pb-rich{font-family:var(--pb-font);font-size:var(--pb-size);line-height:var(--pb-line-height);letter-spacing:var(--pb-letter-spacing)}
.pb-rich p{margin:0 0 .75rem}
.pb-rich a{color:var(--pb-accent);text-decoration:underline}
.pb-shadow{box-shadow:0 8px 24px -12px rgba(15,23,42,.3)}
.bg-stripes{background-image:repeating-linear-gradient(45deg,rgba(255,255,255,.22) 0,rgba(255,255,255,.22) 6px,transparent 6px,transparent 12px)}
[x-cloak]{display:none!important}
</style>
