<template>
    <div>
        <div class="form-group mb-2">
            <form-input-component class="form-control"
                                  placeholder="Имя покупателя"
                                  @value="onName"/>
        </div>

        <div class="form-group mb-2">
            <form-input-component class="form-control"
                                  placeholder="Телефон"
                                  @value="onPhone"/>
        </div>

        <div class="form-group mb-2">
            <form-input-component class="form-control"
                                  placeholder="Email"
                                  @value="onEmail"/>
        </div>

        <div class="form-group mb-2">
            <form-input-component class="form-control"
                                  placeholder="Адрес"
                                  @value="onAddress"/>
        </div>

        <div class="form-group mb-2">
            <form-input-component class="form-control"
                                  type="number"
                                  placeholder="К оплате(оценка сдачи)"
                                  @value="onPaid"/>
        </div>

        <div class="form-group mb-2">
            <form-textarea-controller class="form-control"
                                      placeholder="Комментарий"
                                      @value="onComment"></form-textarea-controller>
        </div>

        <div class="form-group mb-2 text-end">
            <form-button-component class="w-25 btn btn-success"
                                   :disabled="isLoading"
                                   @click.native="debounceSubmitCustomer">Подтвердить</form-button-component>
        </div>
    </div>
</template>
<script>
    import FormInputComponent from "../FormInputComponent";
    import FormTextareaController from "../FormTextareaController";
    import FormButtonComponent from "../FormButtonComponent";

    export default {
        components: {
            FormButtonComponent,
            FormTextareaController,
            FormInputComponent
        },
        data() {
            return {
                isLoading: false,

                name: '',
                phone: '',
                email: '',
                address: '',
                paid: '',
                comment: '',
            }
        },
        created() {
            this.debounceSubmitCustomer = _.debounce(this.submitCustomer, 200);
        },
        methods: {

            redirectToPageIndex: function () {
                window.location.replace(window.location.origin);
            },

            /*
             |---------------------------------------------------------------------------------------------
             | XMLHttpRequests
             |--------------------------------------------------------------------------------------
             */

            submitCustomer() {
                if (this.isLoading)
                    return;

                this.isLoading = true;

                return this.axios.post(`/api/customer`, {
                    name: this.name,
                    phone: this.phone,
                    email: this.email,
                    address: this.address,
                    paid: this.paid,
                    comment: this.comment,
                }).then(res => {
                    this.isLoading = false;

                    // TODO: вывод об успешном принятии заказа
                    console.log(res);

                    // 1. не забываем обнулить количество товаров в карзине
                    this.$root.$app.navbarCartBadgeCounterReset();
                    // 2. открываем модал об успешном принятии заказа
                    this.$swal.fire({
                        title: 'Ваш заказ принят!',
                        text: 'Заказ принят и находится в очереди на обслуживание. Ждите.',
                        type: 'success',
                    }).then((result) => {
                        if (result.value) {
                            this.redirectToPageIndex();
                        }
                    });
                }).catch(error => {
                    console.log(error);
                    this.isLoading = false;
                });
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |--------------------------------------------------------------------------------------
             */

            onName(val) {
                this.name = val;
            },
            onPhone(val) {
                this.phone = val;
            },
            onEmail(val) {
                this.email = val;
            },
            onAddress(val) {
                this.address = val;
            },
            onPaid(val) {
                this.paid = val;
            },
            onComment(val) {
                this.comment = val;
            },
        },
    }
</script>