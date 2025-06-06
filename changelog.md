# Enhanced YouTube Embed Block

## 1.3.0

- Add custom thumbnail setting for YouTube videos

## 1.2.1 (4 June 2025)

- Fix unset array key warning
- Improve plugin demo content to include Vimeo examples

## 1.2.0 (14 May 2025)

- Add support for Vimeo!
- Upgrade `lite-youtube` to 1.8.1 (includes new native support for fallback thumbnail formats and sizes)
- Further performance improvements to load script asynchronously and only load styles when needed
- Fix undefined $params fatal error when trying to extract time code from YouTube URLs
- Code quality improvements

## 1.1.0 (11 July 2024)

- Fix missing file on WordPress.org version of plugin due to misconfigured Github deployment
- MAJOR CHANGE: The default poster image is now the highest quality possible. There is a new `eeb_posterquality` filter to change that, if desired. (#5)
- Add experimental patch to the `lite-youtube` web component that detects missing YouTube poster images and fallsback to different format / lower quality (#4)
- Add `!important` to all CSS styles to improve theme compatibility. Add custom properties to make color changes to fallback styles easier. (#8)
- Add `eeb_nocookie` filter to customize domain for loading the YouTube iframe (defaults to nocookie) (#7)
- Don't use lite-youtube embed in feeds (#9)
- Props to @cbirdsong for numerous issues on Github that led to most of these changes

## 1.0.0 (22 April 2024)

- Initial release to the WordPress repository!

## 0.1.0

- Beta release
