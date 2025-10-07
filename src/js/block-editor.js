import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { BlockControls } from '@wordpress/block-editor';
import { MediaToolbar } from '@10up/block-components';
import AttachmentImage from './attachment-image';
//import './youtube.js';

const blocksWithSetting = [ 'core/embed' ];
const providersWithSetting = [ 'youtube' ];

/**
 * Add the controls in the sidebar
 */
const addThumbnailControl = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const {
			attributes: { thumbnailId, providerNameSlug, url },
			setAttributes,
			name,
		} = props;
        
        if (
            !blocksWithSetting.includes(name) ||
            !providersWithSetting.includes(providerNameSlug)) {
            return <BlockEdit {...props} />;
        }

		return (
			<Fragment>
                <lite-youtube videoid={extract_youtube_id_from_uri(url)} {...props}>
    				<BlockEdit {...props} />
                    <AttachmentImage imageId={thumbnailId} size="large" />
                </lite-youtube>
                <BlockControls>
                    <MediaToolbar
                        isOptional
                        id={ thumbnailId }
                        onSelect={ ( image ) => setAttributes({ thumbnailId: image.id }) }
                        onRemove={ () => setAttributes({ thumbnailId: null }) }
                        labels={{
                            add: __('Set Thumbnail', 'enhanced-embed-block'),
                            remove: __('Remove Thumbnail', 'enhanced-embed-block'),
                            replace: __('Change Thumbnail', 'enhanced-embed-block'), 
                        }}
                    />
                </BlockControls>
			</Fragment>
		);
	};
}, 'addThumbnailControl');

addFilter(
	'editor.BlockEdit',
	'mrw/add-thumbnail-control',
	addThumbnailControl
);

function extract_youtube_id_from_uri( uri ) {
    const parsedUri = new URL( uri ).searchParams;
    if ( parsedUri.has( 'v' ) ) {
        return parsedUri.get( 'v' );
    }
    return false
}