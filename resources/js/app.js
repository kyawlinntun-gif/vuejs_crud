/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// Sweetalert2
// CommonJS
const Swal = require('sweetalert2');

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Progress bar
import VueProgressBar from 'vue-progressbar';

const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'left',
    inverse: false
}

Vue.use(VueProgressBar, options);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const axios = require('axios');

const EventBus = new Vue();

class Form{
    constructor(data)
    {
        this.originData = data;

        for (let field in data)
        {
            this[field] = data[field]
        }

        this.errors = new Error();
        this.items = new Item();
    }

    data() {
        let data = Object.assign({}, this);

        delete data.originData;
        delete data.errors;
        delete data.items;

        return data;
    }

    submit(requestType, url)
    {
        axios[requestType](url, this.data())
        .then(this.onSuccess.bind(this))
        .catch(this.onFail.bind(this));
    }

    onFail(errors)
    {
        this.errors.record(errors.response.data.errors);

        // Fail the progress bar
        // this.$Progress.fail();
    }

    onSuccess(response)
    {
        // alert(response.data.success);

        //  start the progress bar
        // this.$Progress.start();
        
        Toast.fire({
            icon: 'success',
            title: response.data.success
        });

        this.errors.clearAll();
        this.reset();

        //  finish the progress bar
        // this.$Progress.finish();

        this.getItem('get', 'getItems');
    }

    reset()
    {
        for (let field in this.originData)
        {
            this[field] = '';
        }
    }

    getItem(requestType, url)
    {
        //  start the progress bar
        // this.$Progress.start();

        axios[requestType](url)
        .then(response => {
            this.items.get(response.data);

            //  finish the progress bar
            // this.$Progress.finish();
        })
        .catch(errors => {
            console.log(errors);

            // Fail the progress bar
            // this.$Progress.fail();
        });
    }

    deleteItem(requestType, url, itemId)
    {
        // axios[requestType](url, item)
        // .then(response => {
        //     this.getItem();
        // })
        // .catch(errors => {
        //     console.log(errors);
        // })

        //  start the progress bar
        // this.$Progress.start();

        axios({
            method: requestType,
            url: url,
            data: {
                itemId: itemId
            }
        })
        .then(response => {
            // console.log(response);

            Toast.fire({
                icon: 'success',
                title: response.data.success
            });

            //  finish the progress bar
            // this.$Progress.finish();

            this.getItem('get', 'getItems');
        })
        .catch(errors => {
            console.log(errors);

            // Fail the progress bar
            // this.$Progress.fail();
        });
    }

}

class Error{
    constructor()
    {
        this.errors = {};
    }

    record(errors)
    {
        this.errors = errors;
    }

    get(field)
    {
        if(this.errors[field])
        {
            return this.errors[field][0];
        }
    }

    any()
    {
        return Object.keys(this.errors).length > 0;
    }

    clear(field)
    {
        delete this.errors[field];
    }

    clearAll()
    {
        this.errors = {};
    }

}

class Item {
    constructor()
    {
        this.items = {};
    }

    get(items)
    {
        this.items = items;
    }

    any()
    {
        return Object.keys(this.items).length > 0;
    }
}

class EditForm{
    constructor()
    {
        this.item = {};

        this.errors = new Error();
    }

    record(item)
    {
        this.item = item;
    }

    submit(requestType, url)
    {
        axios[requestType](url, this.item)
        .then(this.onSuccess.bind(this))
        .catch(this.onFail.bind(this));
    }

    onFail(errors)
    {
        this.errors.record(errors.response.data.errors);
    }

    onSuccess(response)
    {
        Toast.fire({
            icon: 'success',
            title: response.data.success
        });

        $('#showModal').modal('hide');

        EventBus.$emit('getItems');

        this.errors.clearAll();
        this.reset();
    }

    reset()
    {
        this.item = {};
    }
}

const app = new Vue({
    el: '#app',
    data: {
        form: new Form({
            name: '',
            age: '',
            profession: ''
        }),
        editForm: new EditForm() 
    },
    methods: {
        storeItem() {
            this.form.submit('post', 'storeItem');
        },
        getItem() {
            this.form.getItem('get', 'getItems');
        },
        deleteItem(itemId){
            this.form.deleteItem('delete', 'deleteItem', itemId);
        },
        showModal(item)
        {
            $('#showModal').modal('show');
            this.editForm.record(item);
        },
        hideModal()
        {
            $('#showModal').modal('hide');
        },
        storeEditItem()
        {
            this.editForm.submit('put', 'storeItem');
        }
    },
    mounted()
    {
        this.getItem();
        EventBus.$on('getItems', () => this.getItem());
    }
});
