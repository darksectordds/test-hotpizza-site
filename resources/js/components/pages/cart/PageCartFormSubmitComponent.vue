<template>
    <template-article-component class="my-4">
        <form-customer-component></form-customer-component>
    </template-article-component>
</template>

<script>
    import TemplateArticleComponent from "../../templates/TemplateArticleComponent";
    import FormCustomerComponent from "../../forms/ext/FormCustomerComponent";

    export default {
        name: 'PageCartFormSubmitComponent',
        components: {FormCustomerComponent, TemplateArticleComponent}
    }
</script>