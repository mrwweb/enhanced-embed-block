function extract_youtube_id_from_uri( uri ) {
    const parsedUri = new URL( uri ).searchParams;
    if ( parsedUri.has( 'v' ) ) {
        return parsedUri.get( 'v' );
    }
    return false
}