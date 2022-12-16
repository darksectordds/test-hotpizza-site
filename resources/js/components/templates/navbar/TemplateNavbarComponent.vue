<template>
    <nav class="pizza__navbar">
        <div class="pizza__navbar__logo">
            <template-image-component class="pizza__navbar__logo__img" :image="image"></template-image-component>
        </div>
        <div class="pizza__navbar__items">
            <slot></slot>
        </div>
    </nav>
</template>

<script>
    import TemplateImageComponent from "../TemplateImageComponent";

    export default {
        name: 'TemplateNavbarComponent',
        components: {TemplateImageComponent},
        props: {
            image: {type: String, default: '/images/logo.png'}
        },
    }
</script>