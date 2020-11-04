@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="storeItem" @keydown="form.errors.clear($event.target.name)"> 
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your name" v-model="form.name" name="name">
                        <p class="text-danger" v-if="form.errors.get('name')" v-text="form.errors.get('name')"></p>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" class="form-control" id="age" placeholder="Enter your age" v-model="form.age" name="age">
                        <p class="text-danger" v-if="form.errors.get('age')" v-text="form.errors.get('age')"></p>
                    </div>
                    <div class="form-group">
                        <label for="profession">Profession:</label>
                        <input type="text" class="form-control" id="profession" placeholder="Enter your profession" v-model="form.profession" name="profession">
                        <p class="text-danger" v-if="form.errors.get('profession')" v-text="form.errors.get('profession')"></p>
                    </div>
                    <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">ADD</button>
                </form>
            </div>
            <div class="card-footer" v-show="form.errors.any()">
                <div class="alert alert-danger">Please fill all the fields</div>
            </div>
        </div>
    </div>

    <div class="row mt-5" v-show="form.items.any()">
        <div class="col-6 offset-3">
            <table class="table table-dark">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Age</th>
                    <th scope="col">Profession</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in form.items.items">
                    <th scope="row" v-text="item.id"></th>
                    <td v-text="item.name"></td>
                    <td v-text="item.age"></td>
                    <td v-text="item.profession"></td>
                    <td><i class="fas fa-edit btn text-primary" @click.prevent="showModal(item)"></i><i class="fas fa-trash btn text-danger" @click.prevent="deleteItem(item.id)"></i></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @submit.prevent="storeEditItem" @keydown="editForm.errors.clear($event.target.name)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="hideModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Name:</label>
                            <input type="text" class="form-control" id="editName" placeholder="Enter your name" v-model="editForm.item.name" name="name">
                            <p class="text-danger" v-if="editForm.errors.get('name')" v-text="editForm.errors.get('name')"></p>
                        </div>
                        <div class="form-group">
                            <label for="editAge">Age:</label>
                            <input type="number" class="form-control" id="editAge" placeholder="Enter your age" v-model="editForm.item.age" name="age">
                            <p class="text-danger" v-if="editForm.errors.get('age')" v-text="editForm.errors.get('age')"></p>
                        </div>
                        <div class="form-group">
                            <label for="editProfession">Profession:</label>
                            <input type="text" class="form-control" id="editProfession" placeholder="Enter your profession" v-model="editForm.item.profession" name="profession">
                            <p class="text-danger" v-if="editForm.errors.get('profession')" v-text="editForm.errors.get('profession')"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
