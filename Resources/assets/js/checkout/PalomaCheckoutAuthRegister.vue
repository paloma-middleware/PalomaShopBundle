<template>
    <div>

        <p class="checkout__info">
            {{ $trans('checkout.state_auth.register_info') }}
        </p>

        <form @submit.prevent="submit" class="form form--customer" novalidate>

            <p v-for="error in errors" class="form__error">
                {{ $trans('error.paloma.' + error.message) }}
            </p>

            <fieldset :disabled="loading">

                <div class="columns">
                    <div class="column">
                        <div class="field form__field form__field--required"
                             :class="{ 'form__field--invalid': $v.customer.firstName.$error }">
                            <label class="label" for="c__first_name">{{ $trans('field.first_name') }}</label>
                            <div class="control">
                                <input v-model="customer.firstName" v-focus class="input" type="text" id="c__first_name"
                                       required name="first_name">
                            </div>
                            <p v-if="!$v.customer.firstName.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.customer.firstName.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.customer.firstName.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field form__field form__field--required"
                             :class="{ 'form__field--invalid': $v.customer.lastName.$error }">
                            <label class="label" for="c__last_name">{{ $trans('field.last_name') }}</label>
                            <div class="control">
                                <input v-model="customer.lastName" class="input" type="text" id="c__last_name" required
                                       name="last_name">
                            </div>
                            <p v-if="!$v.customer.lastName.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.customer.lastName.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.customer.lastName.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">

                        <div class="field form__field form__field--required"
                             :class="{ 'form__field--invalid': $v.customer.emailAddress.$error }">
                            <label class="label" for="c__email">{{ $trans('field.email') }}</label>
                            <div class="control">
                                <input v-model="customer.emailAddress" class="input" type="email" id="c__email" required
                                       name="email">
                            </div>
                            <p v-if="!$v.customer.emailAddress.required" class="help is-danger">
                                {{ $trans('error.email.required') }}
                            </p>
                            <p v-if="!$v.customer.emailAddress.email" class="help is-danger">
                                {{ $trans('error.email.invalid') }}
                            </p>
                        </div>

                    </div>
                    <div class="column">

                        <div class="field form__field form__field--required"
                             v-if="confirmEmailAddress"
                             v-show="customer.emailAddress"
                             :class="{ 'form__field--invalid': $v.customer._emailAddress_confirm.$error }">
                            <label class="label" for="c__email_confirm">{{ $trans('field.email_confirm') }}</label>
                            <div class="control">
                                <input v-model="customer._emailAddress_confirm" class="input" type="email" id="c__email_confirm" required
                                       name="email">
                            </div>
                            <p v-if="!$v.customer._emailAddress_confirm.required" class="help is-danger">
                                {{ $trans('error.email.required') }}
                            </p>
                            <p v-if="!$v.customer._emailAddress_confirm.email" class="help is-danger">
                                {{ $trans('error.email.invalid') }}
                            </p>
                            <p v-if="!$v.customer._emailAddress_confirm.confirmEmailAddress" class="help is-danger">
                                {{ $trans('error.email.differs') }}
                            </p>
                        </div>

                    </div>
                </div>

                <div class="columns">
                    <div class="column">

                        <div class="field form__field"
                             :class="{
                                    'form__field--invalid': $v.customer.dateOfBirth.$error,
                                    'form__field--required': $v.customer.dateOfBirth.$params.required.prop()
                                }">
                            <label class="label" for="c__date_of_birth">{{ $trans('field.date_of_birth') }}</label>
                            <div class="control">
                                <input v-model="customer.dateOfBirth" class="input" type="text" id="c__date_of_birth"
                                       :placeholder="$trans('field.date_of_birth.placeholder')"
                                       name="date_of_birth">
                            </div>
                            <p v-if="!$v.customer.dateOfBirth.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.customer.dateOfBirth.isValidDate" class="help is-danger">
                                {{ $trans('error.date_of_birth.invalid') }}
                            </p>
                        </div>

                    </div>
                    <div class="column">

                        <div class="field form__field"
                             :class="{
                                    'form__field--invalid': $v.customer.gender.$error,
                                    'form__field--required': $v.customer.gender.$params.required.prop()
                                  }">
                            <label class="label">{{ $trans('field.gender') }}</label>
                            <div class="control">
                                <label class="radio">
                                    <input v-model="customer.gender" value="female" type="radio"
                                           name="gender">
                                    {{ $trans('field.gender.female') }}
                                </label>
                                <label class="radio">
                                    <input v-model="customer.gender" value="male" type="radio"
                                           name="gender">
                                    {{ $trans('field.gender.male') }}
                                </label>
                            </div>
                            <p v-if="!$v.customer.gender.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                        </div>

                    </div>
                </div>

                <p v-if="guestsAllowed" class="form__info">
                    {{ $trans('checkout.state_auth.register_password_info') }}
                </p>

                <div class="columns">
                    <div class="column">

                        <div class="field form__field"
                             :class="{ 'form__field--invalid': $v.customer.password.$error }">
                            <label class="label" for="c__password">{{ $trans('field.password') }}</label>
                            <div class="control">
                                <input v-model="customer.password" class="input" type="password" id="c__password"
                                       name="password">
                            </div>
                            <p v-if="!$v.customer.password.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.customer.password.minLength" class="help is-danger">
                                {{ $trans('error.password.too_short', {min: $v.customer.password.$params.minLength.min}) }}
                            </p>
                        </div>

                    </div>
                    <div class="column">

                        <div class="field form__field"
                             v-show="customer.password"
                             :class="{ 'form__field--invalid': $v.customer._password_confirm.$error }">
                            <label class="label" for="c__password_confirm">{{ $trans('field.password_confirm') }}</label>
                            <div class="control">
                                <input v-model="customer._password_confirm" class="input" type="password"
                                       id="c__password_confirm" required
                                       name="password_confirm">
                            </div>
                            <p v-if="!$v.customer._password_confirm.confirmPassword" class="help is-danger">
                                {{ $trans('error.password.differs') }}
                            </p>
                        </div>

                    </div>
                </div>

            </fieldset>

            <div class="field is-grouped is-grouped-right form__buttons">
                <div class="control">
                    <router-link :to="{name: 'state_auth'}" class="button is-text">
                        {{ $trans('nav.back') }}
                    </router-link>
                </div>
                <div class="control">
                    <button class="button is-primary"
                            :class="{'is-loading': loading}"
                            type="submit">
                        {{ $trans('checkout.next') }}
                    </button>
                </div>
            </div>

        </form>

    </div>
</template>

<script>

import paloma from '../paloma';
import config from '../paloma-config';
import utils from '../utils';
import {validationMixin} from 'vuelidate';
import {email, maxLength, minLength, required, requiredIf, sameAs} from 'vuelidate/lib/validators';

const isValidDate = utils.validators.isValidDate;

    export default {
        name: "PalomaCheckoutAuthRegister",

        mixins: [validationMixin],

        data() {

            const emailAddress = paloma.checkout.emailAddress();

            return {
                customer: {
                    emailAddress: emailAddress,
                    _emailAddress_confirm: null,
                    password: null,
                    _password_confirm: null
                },
                errors: [],
                loading: false,
                guestsAllowed: config.checkout.allowGuests,
                confirmEmailAddress: config.customer.confirmEmailAddress,
            }
        },

        validations() {
            const validations = {
                customer: {
                    firstName: {
                        required,
                        maxLength: maxLength(30)
                    },
                    lastName: {
                        required,
                        maxLength: maxLength(30)
                    },
                    emailAddress: {
                        required,
                        email
                    },
                    gender: {
                        required: requiredIf(() => {
                            return config.customer.requireGender;
                        }),
                    },
                    dateOfBirth: {
                        required: requiredIf(() => {
                            return config.customer.requireDateOfBirth;
                        }),
                        isValidDate
                    },
                    phoneNumber: {
                        maxLength: maxLength(30)
                    },
                    password: {
                        required: requiredIf(() => {
                            return !config.checkout.allowGuests;
                        }),
                        minLength: minLength(6)
                    },
                    _password_confirm: {
                        confirmPassword: sameAs('password')
                    }
                }
            };

            if (config.customer.confirmEmailAddress) {
                validations.customer._emailAddress_confirm = {
                    required: true,
                    email,
                    confirmEmailAddress: sameAs('emailAddress')
                };
            }

            return validations;
        },

        methods: {
            submit() {

                if (this.customer.password === '') {
                    this.customer.password = null;
                }

                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

                this.loading = true;
                this.errors = [];

                if (this.customer.password) {

                    paloma.customer
                        .register(this.customer)
                        .then(() => {
                            this.$emit('customer-update');
                        })
                        .catch(e => {
                            this.errors = e.errors;
                        })
                        .finally(() => {
                            this.loading = false;
                        });

                } else {

                    paloma.checkout
                        .setGuestCustomer(this.customer)
                        .then(() => {
                            this.$emit('customer-update');
                        })
                        .catch(e => {
                            this.errors = e.errors;
                        })
                        .finally(() => {
                            this.loading = false;
                        });

                }
            }
        }

    }
</script>

<style scoped>

</style>