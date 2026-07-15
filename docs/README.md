# ARCH WordPress Theme — Editing Guide

## 1. Install the free ACF plugin (required)

In wp-admin: **Plugins → Add New → search "Advanced Custom Fields" → Install → Activate.**

Free tier only — no license key, no paid tier needed for anything in this theme.
Without it, the Equines and Wishlist sections will still show (title + description),
just missing their Availability/Meta Line/Price/Icon fields — the site won't break,
you'll just see a reminder in wp-admin until it's installed.

## 2. What's editable in wp-admin now

| Where in wp-admin | Controls |
|---|---|
| **Equines → Add New** | Each horse/pony/donkey card. Title = name, Featured Image = photo, main content box = the description text. Scroll down to "Equine Details" for **Status** (Available to Adopt / Available to Foster / In Recovery / Permanent Resident / Adopted / In Memoriam — controls both the coloured tag and which section of the Adoption page it lands in), **Type** (Horse / Pony / Donkey & Mule — only used to sub-group the "Available to Adopt" section), and Meta Line (free text, e.g. "Dark bay mare · 145cm · Born 2013" — shown on the horse's *card*, separate from the fields below). Below that, an "Individual Page Details" tab holds the facts shown on the horse's own page: **Sex**, **Breed**, **Height (cm)**, **Year Born** (the page works out the current age itself — no need to update it yearly), **Rescue Date**, and three optional ones — **Favourite Food**, **Dislikes**, **Personality** (a sentence or two, shown as a highlighted quote). All of these can be left blank; any that are empty just don't show on the page, nothing breaks. |
| **Wishlist Items → Add New** | Each wishlist card. Title = item name, main content box = description. "Wishlist Item Details" has Price (just the number, e.g. `25`) and Icon (dropdown of the 6 built-in icon styles). The price also becomes the pre-filled Donorbox donation amount automatically. |
| **Posts → Add New** | News section — this is WordPress's normal blog Posts feature, nothing custom. The 3 most recent posts show on the homepage automatically. |
| **Sponsors → Add New** | The logo grid near the bottom of the homepage. Title = sponsor/organisation name, Featured Image = their logo. Scroll down to "Sponsor Details" for Category (Sponsor / Subsidy / Affiliation — controls which of the three grouped rows it appears under) and an optional Website URL (makes the logo clickable, opens in a new tab). If you don't upload a logo, the sponsor's name shows as plain text instead — the section only appears at all once at least one sponsor exists. |
| **Donation Methods → Add New** | Each "way to give" on the Donate page (Bank Transfer, Shop, Teaming, etc). See section 7 below — this one has more setup detail than the others. |

Equines shows the 4 most recently-added entries. Wishlist shows all entries, in the
order you set (drag to reorder in the admin list, or just by date). Both fall back to
a friendly "nothing here yet" message if empty, rather than breaking.

## 3. What's still hardcoded (the deliberate concession)

Hero heading/subtext, the "Adopt Torero" photo tag, the quote section, About text,
stats numbers, contact details, and the marquee ticker text all still live directly
in `template-parts/content-home.php`. These change rarely, and making them
CMS-editable via ACF requires either ACF Pro (paid, for an Options Page) or a
different free workaround (attaching fields to the static front page instead) —
happy to wire that up too if you want it, just wasn't in the first pass.

## 4. Editing Hero, About, and Contact text (via a Block Pattern)

These sections use WordPress's built-in block editor and Patterns — completely
free, no plugin needed. One-time setup:

1. **Pages → Add New**. Title it whatever you like. Set the URL slug to exactly
   `home-content` (Permalink section, click "Edit" next to the URL).
2. Click the **+** to insert a block, search **"ARCH: Editable Home Content"**
   under Patterns, insert it.
3. Edit the text directly — it's a normal block editor, click into any line and
   type.
4. For the two photos (hero and about section), scroll down below the main
   content area to a separate box called **"Home Content Images"** — this is
   the same mechanism as the Equine photos (an image field, not a block in
   the text), so set it there rather than adding an Image block up in the
   text area itself.
5. In that same "Home Content Images" box there's a **"Hero Photo Link"**
   text field. Set this to make the hero photo (the "Star of the Month"
   horse) clickable — a full URL (`https://...`) or an internal link like
   `/#equines` or `/#contact` both work. Leave it blank and the photo just
   isn't a link at all; nothing breaks either way.
6. **Publish** the page. You don't need to add it to any menu — it just needs to
   exist for the homepage to read from it.

**Important:** don't delete a text block outright — if you do, that one field
just falls back to the original placeholder text rather than breaking the
page, but you'll lose the ability to edit it until you re-add a block with the
matching anchor (Advanced panel → HTML anchor, e.g. `hero-lede`). Reordering
blocks is fine and safe. The two photo fields have their own independent
fallback — leaving either one empty just shows the original photo.

**Not editable, by design:** the hero headline ("Every horse, pony & donkey
deserves a happily ever after.") is hardcoded in the template, not part of the
pattern. It was originally wired up as editable, but the last few words are
styled in italic gold, and editing it via a block flattened that styling to
plain text — so it was reverted to hardcoded to keep the design intact. Change
it directly in `template-parts/content-home.php` if the wording needs updating.

**Not yet wired to this system:** the 4 stat numbers (2009 / UP / 100% / 24-7)
and the scrolling marquee ticker text still live directly in the PHP. They
change rarely enough that I left them out of the first pass — say if you want
these added too.

## 5. Setting up the Adoption page

The homepage's "Meet the Gang" section only ever shows 4 equines (the most
recently added ones with Status "Available to Adopt" or "Available to
Foster"). The full library — every equine, grouped by status, with
"Available to Adopt" further split into Horses / Ponies / Donkeys & Mules —
lives on a separate page you need to create once:

1. **Pages → Add New**. Title it "Adopt" (or whatever you like).
2. In the **Page Attributes** panel (right sidebar), set **Template** to
   **"Equine Adoption"**.
3. Write whatever intro text you want in the main content area — how
   adoption works, what's expected of adopters, etc. Normal block editor,
   full formatting available. Leave it empty and a generic placeholder
   shows instead, so the page never looks broken before you've written this.
4. Scroll down to **"Adoption Page Settings"** and set the URL for the
   **"Apply to Adopt Now"** button — a Google Form link, an application
   page, whatever you're using to actually take applications. Leave it
   blank and the button safely links to the homepage Contact section
   instead, rather than going nowhere.
5. **Publish.**

Every equine's card on this page, and the ones featured on the homepage,
link straight to this page — clicking a horse on the homepage jumps you
straight to that same horse further down the full list here. The nav's
"Equines" link and "Adopt" buttons across the site also point here once
this page exists; before that, they safely fall back to the homepage's
"Meet the Gang" section instead.

**Note on existing content:** the Equine "Availability" field from before
this update has been replaced by the new "Status" field (6 options instead
of 2), under a new name. Any equines you already added will need their
Status re-set — the old data isn't lost, it just isn't read by the new
field, so it'll show as the default "Available to Adopt" until you update it.

## 6. Setting up the News archive page

Similar idea to the Adoption page, but simpler — this uses WordPress's
built-in blog feature, not a custom page template:

1. **Pages → Add New**. Title it "News" (or whatever you like), publish it
   with no content — this page is just a placeholder WordPress needs a URL
   for, it won't show its own content.
2. **Settings → Reading** → set **"Posts page"** to that page → Save Changes.
3. Done. Every post you write (Posts → Add New) now automatically appears
   on that page, newest first, with pagination once you have enough for a
   second page.

Each post supports full rich content — the normal block editor, so you can
mix in text, images, galleries, and video. For video, either use the Video
block to upload a file directly, or just paste a YouTube/Vimeo URL on its
own line in the content and WordPress embeds it automatically.

Every homepage News card, the nav's "News" link, and the footer's "News"
link all point to this page once it's set up — before that, they safely
fall back to the homepage's "Tales From ARCH" section instead.

## 7. Setting up the Donate page

Same page-template mechanism as Adoption. Given how central this page is,
it's built to keep working sensibly at every stage of setup — before you've
touched anything, every "Donate" button on the site links straight to a
generic Donorbox page, never a dead link.

1. **Pages → Add New**. Title it "Donate" (or whatever you like).
2. **Page Attributes** panel → set **Template** to **"Donate"**.
3. Write an intro in the main content area if you want one — optional,
   a sensible default shows if you leave it blank.
4. **Publish.**
5. Now add your actual ways to give: **Donation Methods → Add New**, one
   per method (Bank Transfer, ARCH Shop, Fundraising, Sponsorship, Teaming,
   etc). For each:
   - **Title** = the method name (this also becomes its jump-nav label).
   - **Main content box** = the actual details. Full formatting — for bank
     transfers this is where you'd put the account name, IBAN, SWIFT/BIC,
     however you want it laid out.
   - Scroll to **"Donation Method Details"** for an **Icon** (pick whichever
     best matches), and an optional **Button Text + Button Link** — set
     both together for a method that should show a clickable button (Shop,
     Teaming, Fundraising), or leave both blank for a method that's just
     information to read (Bank Transfer).

The page automatically builds a "jump to" quick-nav across the top from
whatever Donation Methods exist, in the order you set (drag to reorder
under Donation Methods in wp-admin) — this is what keeps the page easy to
scan even with several ways to give listed. A prominent "Donate by Card
Now" button sits right at the top regardless, for anyone who just wants
the fastest option without reading further.

Every "Donate" button site-wide — header, hero, footer, the "Give Now"
button — points to this page once it exists. The Wishlist "Buy" buttons
are the one exception: those keep going straight to Donorbox with their
specific pre-filled item price, since that's a different action from a
general donation.

## 8. Setting up the Volunteer page (and the reusable "Content Page with Button" template)

This template is genuinely reusable — apply it to as many pages as you
like, for anything that's "some content, maybe a button": Volunteer, an
FAQ page, a partnership info page, whatever comes up later. Both the
button's text and its link are optional and set per-page.

**Setting up any page with this template:**

1. **Pages → Add New**. Title it whatever the page is actually about.
2. **Page Attributes** panel → set **Template** to **"Content Page with
   Button"**.
3. Write the page's content in the main editor — normal block editor,
   full formatting. Leave it blank and a generic placeholder shows instead.
4. Scroll to **"Page Button Settings"**:
   - **Button Text** — whatever you want the button to say. Leave blank
     and it defaults to "Learn More" (only relevant if you've set a link
     below — with no link, there's no button regardless of this field).
   - **Button Link** — where the button goes. **Leave this blank and no
     button shows at all** — just your content, nothing underneath it.
5. **Publish.**

**Specifically for the Volunteer page:** the rest of the site (homepage
"Become a volunteer" and "Get Involved" links) needs to know which page
*is* the Volunteer page, out of potentially several pages using this same
template. That's done by URL slug, not by template — when creating the
page, make sure its slug is exactly `volunteer` (Permalink section, "Edit"
next to the URL). Get this right and those links find it automatically;
get the slug wrong (or don't create the page yet) and they safely fall
back to the homepage's "Join the Herd" section instead, rather than
breaking.

**Not built here:** individual volunteer roles as separate manageable
entries (Yard Volunteer, Event Helper, Fundraising, etc.) — this page
assumes one general call-to-action is enough for now. If you want specific
roles listed the way Donation Methods are, that's a straightforward
follow-up using the same CPT pattern, just say so.

## 9. Individual Equine pages (automatic, nothing to set up)

Every Equine now has its own page automatically — WordPress does this on
its own for any custom post type marked public, no template assignment
needed, no page to create. Clicking any equine card anywhere on the site
(homepage teaser, the Adoption page) goes straight to that animal's own
page now, not just an anchor down a shared list.

Each page includes:
- The full photo, name, status, and description
- Social share buttons (Facebook, WhatsApp, X, email) — plain links using
  each platform's own share-URL scheme, no plugin, no tracking scripts
- An "Apply to Adopt [Name]" button — only shown for equines with Status
  "Available to Adopt" or "Available to Foster"; hidden entirely for
  Permanent Residents, Adopted, or In Memoriam entries, since applying to
  adopt those doesn't make sense
- Open Graph and Twitter Card meta tags, so sharing the link on Facebook,
  WhatsApp, or X shows that horse's actual photo and description in the
  preview, not a blank or generic card

**Nothing to configure** — this all works automatically the moment an
Equine is published, using the same Status, Meta Line, and description
you're already filling in.

## 10. Managing the main navigation menu (About / Equines / Wishlist / etc.)

This is now a real WordPress menu, not text hardcoded in the theme — you can
add, remove, reorder, or rename nav items yourself:

1. **Appearance → Menus** in wp-admin.
2. Create a menu (any name), add items to it:
   - For "Equines", add the **Adopt Page itself** (from the Pages panel on
     the left, not a Custom Link) — this way it stays correct automatically
     even if you ever rename the page or change its URL slug.
   - For "News", same idea — add the **News Page itself** (the one you set
     as "Posts page" in Settings → Reading), not a Custom Link.
   - For "Get Involved", same idea again — add the **Volunteer Page
     itself**, not a Custom Link.
   - For About / Wishlist / Contact, add **Custom Links** pointing to
     `/#about`, `/#wishlist`, and `/#contact` respectively — these are
     sections on the homepage, so the leading `/` matters (it makes the
     link work correctly from every page on the site, not just when
     someone's already on the homepage).
3. Under **Menu Settings**, tick **"Primary Menu"** as the display location.
4. **Save Menu.**

Until you do this, the site shows a sensible built-in fallback menu (the
same links as above), so nothing looks broken or empty in the meantime —
setting this up is optional, not required for the site to work.

The "Adopt" and "Donate" buttons next to the menu are deliberately **not**
part of this menu — they're styled call-to-action buttons with smart
destinations (Adopt always points to your real Adoption page automatically;
Donate has a fixed Donorbox link), which a plain menu link can't replicate.
If you want to change where either of those buttons points, that's still a
small code edit in `header.php`, not a wp-admin setting.

## 11. Adding a genuinely new wishlist icon

The 6 icon choices are hand-drawn SVGs baked into the theme (not uploaded images),
to keep things fast and consistent-looking without needing an image library. Adding
a 7th requires a short code edit: `arch_wishlist_icon()` in `functions.php` (add the
SVG), plus the matching entry in the ACF `icon` field's choices, also in
`functions.php`. Not editable from wp-admin alone.

## 12. Sanity checklist after activating

- Visit the homepage — Equines and Wishlist sections should be empty (no content
  yet) with the "nothing here yet" messages, until you add entries.
- Add one Equine and one Wishlist Item to confirm the cards render correctly.
- Check a Wishlist "Buy" button — it should open Donorbox with the price you set
  already filled in.
- Create the Adoption page (see section 5), then check a homepage equine card —
  clicking it should land you on that same equine on the Adoption page, not
  just the top of it.
