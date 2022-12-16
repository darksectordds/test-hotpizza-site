<template>
    <!-- TODO: перевести на a:href для семантики? -->
    <div class="pizza__navbar__nav-item">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'TemplateNavbarItemComponent',
    }
</script>