# \<lite-vimeo\>

<p>
  <a href="https://npmjs.com/package/@cshawaus/lite-vimeo"><img src="https://img.shields.io/npm/v/@cshawaus/lite-vimeo.svg" alt="npm package"></a>
  <a href="https://nodejs.org/en/about/releases/"><img src="https://img.shields.io/node/v/@cshawaus/lite-vimeo.svg" alt="node compatility"></a>
</p>

> A web component that displays Vimeo embeds faster. Based on Justin Ribeiro's excellent [\<lite-youtube\>](https://github.com/justinribeiro/lite-youtube), which, in turn, is a shadow DOM version of Paul's [lite-youtube-embed](https://github.com/paulirish/lite-youtube-embed) based on Alex Russell's [\<lite-vimeo\>](https://github.com/cshawaus/lite-vimeo/) package.

This is basically a rebadge of Justin's component, but for Vimeo.

## Features

- No dependencies; it's just a vanilla web component.
- It's fast yo.
- It's shadow DOM encapsulated! (supports CSS `::part`)
- It's responsive 16:9
- It's accessible via keyboard and will set ARIA via the `videotitle` attribute
- It's locale ready; you can set the `videoplay` to have a properly locale based label
- Set the `start` attribute to start at a particular place in a video
- You can set `autoload` to use Intersection Observer to load the iframe when scrolled into view.
- Loads placeholder image as WebP with a Jpeg fallback

## Install

This web component is built with ES modules in mind and is
available on NPM.

```sh
pnpm i @cshawaus/lite-vimeo
# or
npm i @cshawaus/lite-vimeo
# or
yarn add @cshawaus/lite-vimeo
```

After installing import into your project using the following:

```js
import '@cshawaus/lite-vimeo'
```

## Usage with CommonJS

CommonJS is supported for those who aren't ready to adopt ESM yet.

```js
require('@cshawaus/lite-vimeo')
```

## Usage with jsDelivr

If you want the paste-and-go version, you can simply load it via jsDelivr.

```html
<!-- always the latest version -->
<script src="https://cdn.jsdelivr.net/npm/@cshawaus/lite-vimeo/lib/index.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@cshawaus/lite-vimeo/lib/index.esm.js"></script>

<!-- pinned to a specific version -->
<script src="https://cdn.jsdelivr.net/npm/@cshawaus/lite-vimeo@1.0.0/lib/index.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@cshawaus/lite-vimeo@1.0.0/lib/index.esm.js"></script>
```

## Basic Usage

```html
<lite-vimeo videoid="364402896"></lite-vimeo>
```

## Add Video Title

```html
<lite-vimeo videoid="364402896" videotitle="This is a video title"></lite-vimeo>
```

## Change "Play" for Locale</h3>

```html
<lite-vimeo videoid="364402896" videoplay="Mirar" videotitle="Mis hijos se burlan de mi español"></lite-vimeo>
```

## Customise Everything

Bring your own CSS to the party anc customise the web component to your liking.

```html
<style>
  .vimeo-player {
    margin: auto;
    max-width: 1024px;
    width: 100%;
  }
</style>
<div class="vimeo-player">
  <lite-vimeo videoid="364402896"></lite-vimeo>
</div>
```

### Using shadow DOM `::part`

Because the shadow DOM exists outside of the normal page context it prevent global CSS from being applied. To overcome this you can make use of the `::part` CSS pseudo-element that can traverse the shadow tree and apply styles from the global context.

```css
.vimeo-player ::part(frame) {
  border: 2px solid red;
}
```

#### Available parts

| CSS pseudo-element      | Description                                |
| ----------------------- | ------------------------------------------ |
| `::part(frame)`         | Targets the main player frame              |
| `::part(picture-frame)` | Targets the `<picture>` element            |
| `::part(picture)`       | Targets the `<img>` element in `<picture>` |
| `::part(play-button)`   | Targets the play button                    |

## Set a video start time

```html
<!-- Start at 5 min, 30 seconds -->
<lite-vimeo videoid="364402896" start="5m30s"></lite-vimeo>
```

## Auto load with `IntersectionObserver`

`IntersectionObserver` is used to automatically load the Vimeo iframe when scrolled into view. This can reduce performance in some circumstances with multiple players on the same page.

```html
<lite-vimeo videoid="364402896" autoload></lite-vimeo>
```

## Auto Play (requires auto load)

When allowed by the browser the player will automatically start the vido upon it coming into view.

```html
<lite-vimeo videoid="364402896" autoload autoplay></lite-vimeo>
```

## Attributes

The web component allows certain attributes to be give a little additional
flexibility.

| Name         | Description                                                                 | Default |
| ------------ | --------------------------------------------------------------------------- | ------- |
| `videoid`    | The Vimeo videoid                                                           | ``      |
| `videotitle` | The title of the video                                                      | `Video` |
| `videoplay`  | The title of the play button (for translation)                              | `Play`  |
| `autoload`   | Use Intersection Observer to load iframe when scrolled into view            | `false` |
| `autoplay`   | Video attempts to play automatically if auto-load set and browser allows it | `false` |
| `start`      | Set the point at which the video should start, in seconds                   | `0`     |
