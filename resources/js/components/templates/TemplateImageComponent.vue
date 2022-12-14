<template>
    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
         :style="{backgroundImage: 'url(' + urlFix(imageSrc) + ')'}"
    >
</template>

<script>
    export default {
        name: 'TemplateImageComponent',
        props: {
            image: { type: String, default: null },
            imageDefault: { type: String, default: '/images/no_photo.png' },
        },
        computed: {
            hasImage: function () {
                return !!this.image &&
                    typeof this.image !== 'undefined' &&
                    this.image.length > 0;
            },
            imageSrc: function () {
                return (this.hasImage) ? this.image : this.imageDefault;
            },
        },
        methods: {
            urlFix(url) {
                return (url[0] !== '/') ? `/${url}` : url;
            },
        },
    }
</script>