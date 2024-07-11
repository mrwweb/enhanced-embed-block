=== Enhanced Embed Block for YouTube ===
Contributors: mrwweb, cbirdsong
Donate link: https://paypal.me/rootwiley
Tags: YouTube, embed, video, block, performance
Requires at least: 6.5
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Enhance the default YouTube Embed Block to load faster.

== Description ==

If you care about performance, privacy, and user experience, this block is for you.

This plugin enhances the default YouTube block—including any existing blocks—and changes their behavior to only load the video thumbnail until a visitor chooses to play the video.

= Features =

* Load YouTube videos faster (uses the `lite-youtube` custom-element)
* Loads videos from nocookie.youtube.com for enhanced privacy
* Works without JavaScript (shows link to YouTube video instead)
* No plugin lock-in! Automatically improves all YouTube embeds. Turn it off and the behavior goes back to the WordPress default.

= Want more features? =

I'm considering building a PRO version with the potential following features:

* Options to set a custom start and end time
* Custom thumbnail images for videos
* Custom aspect ratios for videos
* Support for YouTube shorts
* Control whether to load video from nocookie.youtube.com or not
* Adjust the image quality of the thumbnail
* Support for playlists, not just single videos
* Full support for all YouTube query parameters (https://developers.google.com/youtube/player_parameters)
* Classic Editor / [embed] shortcode support
* Support similar features for Vimeo and other embed sources where possible

If enough people express interest, I'll build it! [Let me know if you're interested!](https://mrwweb.com/wordpress-plugins/enhanced-embed-block/#pro)

= Contribute on Github =

[Enhanced Embed Block is on Github](https://github.com/mrwweb/enhanced-embed-block) for pull requests and bug reports.

== Installation ==

1. From your WordPress site’s dashboard, go to Plugins > Add New.
2. Search for “Enhanced Embed Block for YouTube”
3. Click “Install”
4. Click “Activate”
5. That’s it! You’re done! There are no plugin settings and the enhancements immediately apply to all YouTube video embeds.

== Frequently Asked Questions ==

= Does this create a new block? =

No. It enhances the default WordPress embed block for YouTube videos.

= Does it automatically enhance all my YouTube embeds? =

It works for any embeds using the YouTube block. Embeds using the [embed] shortcode or literal YouTube embed code in HTML are not enhanced. Using the core WordPress YouTube Embed block is highly recommended!

= Why doesn't Google load all videos this way by default? =

Great question! It sure seems like they should. If I had to guess, they are prioritizing usage tracking over fast load times and privacy.

= What happens if I deactivate the plugin? =

Your embed blocks go back to how they were before.

= Does this support the Classic Editor / the [embed] shortcode? =

Not right now. If you'd pay for a PRO version with this feature, [let me know](https://mrwweb.com/wordpress-plugins/enhanced-embed-block/#pro).

== Software ==

This plugin uses the [`lite-youtube` custom-element](https://github.com/justinribeiro/lite-youtube) under the MIT license. Thank you to Paul Irish and Justin Ribiero for their work on that project.

== Changelog ==

= 1.1.0 =
- Fix missing file on WordPress.org version of plugin due to misconfigured Github deployment
- MAJOR CHANGE: The default poster image is now the highest quality possible. There is a new `eeb_posterquality` filter to change that, if desired. (#5)
- Add experimental patch to the `lite-youtube` web component that detects missing YouTube poster images and fallsback to different format / lower quality (#4)
- Add `!important` to all CSS styles to improve theme compatibility. Add custom properties to make color changes to fallback styles easier. (#8)
- Add `eeb_nocookie` filter to customize domain for loading the YouTube iframe (defaults to nocookie) (#7)
- Don't use lite-youtube embed in feeds (#9)
- Props to @cbirdsong for numerous issues on Github that led to most of these changes

= 1.0.0 =
- Initial release to the WordPress repository!

== Upgrade Notice ==

= 1.1.0 =
Fix plugin in WordPress repository. Use higher quality poster image with new fallback detection. Don't apply embed changes to feeds. Developer improvements.