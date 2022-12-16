<template>
    <template-navbar-component>
        <template-navbar-item-component v-for="(item,idx) in navigations"
                                        :key="item.key"
                                        :class="[item.class]"
                                        @click.native="interfaceRouteTo(idx)">
            <!-- icon -->
            <template v-if="!!item.icon">
                <font-awesome-icon :icon="item.icon" />
            </template>

            <!-- title -->
            <template v-else-if="!!item.title">{{ item.title }}</template>

            <!-- внешнее расширение -->
            <slot :name="navbarSlotNameRuleFunc(idx)"></slot>

        </template-navbar-item-component>
    </template-navbar-component>
</template>

<script>
    import TemplateNavbarComponent from "../templates/navbar/TemplateNavbarComponent";
    import TemplateNavbarItemComponent from "../templates/navbar/TemplateNavbarItemComponent";

    export default {
        name: 'NavbarComponent',
        components: {TemplateNavbarComponent, TemplateNavbarItemComponent},
        props: {
            navigations: { type: Array, default: () => ([]) },
            navbarSlotNameRuleFunc: { type: Function },
        },
        methods: {

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |--------------------------------------------------------------------------------------
             */

            interfaceRouteTo(idx) {
                this.$emit('navigate', idx);
            },
            interfaceMounted() {
                this.$emit('mounted');
            }
        },
        mounted() {
            this.interfaceMounted();
        }
    }
</script>