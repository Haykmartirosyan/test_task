<template>
    <!-- Modal -->
    <div class="modal fade" id="deleteItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Համոզվա՞ծ եք</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Ոչ</button>
                    <button type="button" class="btn btn-primary" @click="deleteItem">Այո</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {EventBus} from "../app";

export default {
    name: "deleteConfirmation",
    data() {
        return {
            dataId: ''
        }
    },
    methods: {
        deleteItem() {
            axios.delete('/user/data/' + this.dataId + '/delete')
                .then(response => {
                    if (response.data.success) {
                        $('#deleteItem').modal("hide");
                        this.dataId  = '';
                        EventBus.$emit('reinit-data');
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        }
    },
    mounted() {
        let _this = this;

        EventBus.$on('delete-data', function (data) {
            if (data.id) {
                _this.dataId = data.id;
                $('#deleteItem').modal("show")
            }
        });
    }
}
</script>

<style scoped>

</style>
