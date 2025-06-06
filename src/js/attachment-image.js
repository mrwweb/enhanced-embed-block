
import { useSelect } from '@wordpress/data';

/**
 * AttachmentImage
 *
 * This component is used to display an image from the media library.
 * It's meant as a JS companion to the PHP function `wp_get_attachment_image()`.
 *
 * @link https://www.briancoords.com/getting-wordpress-media-library-images-in-javascript/
 * @link https://github.com/bacoords/example-image-block/blob/main/src/AttachmentImage.js
 *
 * @param {object} props
 * @param {number} props.imageId The ID of the image to display.
 * @param {string} props.size The size of the image to display. Defaults to 'full'.
 * @returns {*} React JSX
 */
export default function AttachmentImage({ imageId, size = 'full' }) {

	const { image } = useSelect((select) => ({
		image: select('core').getMedia(imageId),
	}));

	const imageAttributes = () =>{
		let attributes = {
			src: image.source_url,
			alt: image.alt_text,
			className: `attachment-${size} size-${size}`,
			width: image.media_details.width,
			height: image.media_details.height,
		};
		if (image.media_details && image.media_details.sizes && image.media_details.sizes[size]) {
			attributes.src = image.media_details.sizes[size].source_url;
			attributes.width = image.media_details.sizes[size].width;
			attributes.height = image.media_details.sizes[size].height;
		}

		return attributes;
	};

	return (
		<>
			{image && (
				<img {...imageAttributes()} />
			)}
		</>
	)
}