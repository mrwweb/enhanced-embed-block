import { addFilter } from '@wordpress/hooks';

const blocksWithSetting = ['core/embed'];

/**
 * Register the Attribute
 *
 * @param {Object} settings
 */
function embedCustomThumbnailAttribute(settings) {
    const { name } = settings;
	if (blocksWithSetting.includes(name)) {
		settings.attributes = Object.assign({}, settings.attributes, {
			thumbnailId: {
				type: 'number',
				default: null,
			}
		});
	}

	return settings;
}

addFilter(
	'blocks.registerBlockType',
	'mrw/custom-thumbnail-attribute',
	embedCustomThumbnailAttribute
);
