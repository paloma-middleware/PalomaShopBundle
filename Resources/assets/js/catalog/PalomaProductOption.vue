<template>
    <div class="product-option" :class="{'is-hidden': values.length < 2}">

        <h3 v-if="option.option !== '_variant'" class="product-option__title">{{ option.label }}</h3>

        <div v-for="value in values" :key="value.value" class="product-option__value"
            :class="{
                    'product-option__value--selected': value.value === selected,
                    'product-option__value--variant': value.variant,
                    'product-option__value--disabled': !value.available
                }"
            @click.prevent="selectValue(value)">
            <div class="product-option__value-control control">
                <label class="radio">
                    <input type="radio" v-bind:name="'option__' + option.option" v-bind:checked="value.value === selected" />
                    {{ value.label }}
                </label>
            </div>
            <div v-if="value.variant" class="product-option__price">
                <paloma-price :price="value.variant.price" :original="value.variant.originalPrice"></paloma-price>
            </div>
        </div>

    </div>
</template>

<script>
    import PalomaPrice from "../common/PalomaPrice";

    export default {
        name: "PalomaProductOption",

        components: {PalomaPrice},

        props: {
            option: Object
        },

        data() {

            const values = this.option.values.map(value => {

                value.available = value.variant && value.variant.availability.available;

                return value;
            });

            return {
                selected: this.option.selectedValue,
                values: values
            }
        },

        methods: {
            selectValue(value) {
                this.selected = value.value;
                this.$emit('select-value', this.option, value);
            }
        }
    }
</script>
