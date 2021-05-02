<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card-body">
                    <button class="plus-button btn btn-success" @click="addRow">+ Ավելացնել</button>
                    <button class="plus-button btn btn-link" @click="orderData">Տեսակավորել ըստ Արժեքի</button>
                    <table class="table">
                        <tbody>
                        <template v-for="data in userData">
                            <tr>
                                <td>
                                    <input type="text" v-model="data.value"
                                           :class="!data.value ? null : 'value-input'"
                                           :id="'item-' + data.id ? data.id : null"
                                           @focus="selectItem(data.id ? data.id : null)"
                                           @blur="unselectItem(data.id ? data.id : null)">
                                </td>
                                <td>
                                    <template v-if="data.id">
                                        <button class="btn btn-info text-white" :id="'change-' + data.id"
                                                @click="changeItem(data.id, data.value)">Փոփոխել
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button class="btn btn-info text-white"
                                                @click="addItem(data.value)">Պահպանել
                                        </button>
                                    </template>
                                </td>
                                <td>
                                    <button class="btn btn-danger"
                                            @click="deleteUserData(data.id ? data.id : null, $event)">
                                        Ջնջել
                                    </button>
                                </td>
                            </tr>
                        </template>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <delete-confirmation></delete-confirmation>
    </div>

</template>

<script>
import {EventBus} from "../app";

export default {
    data() {
        return {
            userData: [],
            selectedItem: '',
            order: null
        }
    },
    methods: {
        addRow() {
            this.userData.unshift({'value': ''});
        },
        deleteUserData(id, event) {
            if (id) {
                EventBus.$emit('delete-data', {id: id});
            } else {
                event.target.parentNode.parentNode.remove()
            }
        },
        getUserData() {
            let url = this.order ? '/user/data?order=' + this.order : '/user/data';
            axios.get(url)
                .then(response => {
                    if (Object.keys(response.data.data).length) {
                        this.userData = response.data.data
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        selectItem(id) {
            this.selectedItem = id;
            $('#change-' + id).text("Պահպանել")
        },
        unselectItem(id) {
            this.selectedItem = '';
            if (id) {
                $('#change-' + id).text("Փոփոխել")
            }
        },
        changeItem(id, value) {
            let data = {
                'value': value
            }
            axios.put('/user/data/' + id + '/update', data)
                .then(response => {
                    if (response.data.success) {
                        EventBus.$emit('reinit-data');
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        addItem(value) {
            let data = {
                'value': value
            }
            axios.post('/user/data/create', data)
                .then(response => {
                    if (response.data.success) {
                        EventBus.$emit('reinit-data');
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        orderData() {
            this.order = this.order ? '' : 'asc';
            EventBus.$emit('reinit-data');
        }
    },
    mounted() {
        let _this = this;
        this.getUserData();

        EventBus.$on('reinit-data', function () {
            _this.userData = [];
            _this.getUserData();
        });

        $(document).keyup(function (event) {
            if (event.which === 13) {
                if (_this.selectedItem) {
                    let item = _this.userData.find(element => element.id = _this.selectedItem);
                    if (item) {
                        let value = $('#item-' + item.id).val()
                        _this.changeItem(_this.selectedItem, value)
                    }
                }
            }

            if (event.which === 27) {
                if (_this.selectedItem) {
                    _this.selectedItem = '';
                    EventBus.$emit('reinit-data');
                }
            }
        });
    }
}
</script>
<style scoped>
.plus-button {
    margin-bottom: 20px;
}

.value-input {
    border: none;
    background-color: #f8fafc;
}

.value-input:hover {
    box-shadow: 1px 1px 2px 3px #ccc;
}
</style>
