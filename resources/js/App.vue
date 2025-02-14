<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const positions = ref();
const getResponse = ref();
const postResponse = ref();

const initForm = () => ({
    token: null,
    name: null,
    email: null,
    phone: null,
    position_id: null,
    photo: null
});

const selectedPos = ref();

let form = initForm();

const handlePhotoUpload = (event) => {
    form.photo = event.target.files[0];
};

const getUsers = async (url) => {
    try {
        getResponse.value = await axios.get(url);
    } catch (error) {
        console.log(error);
    }
};

const getPositions = async () => {
    try {
        const response = await axios.get('/api/v1/positions');
        positions.value = response.data.positions;
    } catch (error) {
        console.log(error);
    }
};

const getToken = async () => {
    try {
        const response = await axios.get('/api/v1/token');
        return response.data.token;
    } catch (error) {
        console.log(error);
    }
};

const postUser = async () => {
    const position = positions.value.find(pos => pos.name === selectedPos.value);
    if (position) {
        form.position_id = position.id;
    }
    
    const headers = {
        'Token': await getToken(),
        'Content-Type': 'multipart/form-data'
    };

    try {
        postResponse.value = await axios.post('/api/v1/users', form, { headers });
        form = initForm();
        document.getElementById('photoInput').value = null;
        selectedPos.value = null;
    } catch (error) {
        postResponse.value = error.response;
    }
};

onMounted(async () => {
    await getUsers('/api/v1/users?count=6');
    await getPositions();
});

</script>

<template>
    <div v-if="getResponse" class="m-4">
        <div class="flex gap-4 items-center justify-between mb-4">
            <h2 class="text-xl font-bold">User List 🚀</h2>
            <div class="flex gap-4">
                <button v-if="getResponse.data.links.prev_url" @click="getUsers(getResponse.data.links.prev_url)" class="w-32 py-1 border border-black rounded">← Previous</button>
                <button v-if="getResponse.data.links.next_url" @click="getUsers(getResponse.data.links.next_url)" class="w-32 py-1 border border-black rounded">Next → </button>
            </div>
        </div>
        
        <div class="flex flex-wrap justify-between gap-y-4">
            <div v-for="user in getResponse.data.users" :key="user.id" class="border p-4 rounded shadow-md flex w-[49%] gap-4">
                <img :src="user.photo" alt="User Photo" class="w-[70px] h-[70px] object-cover rounded-full" />
                <div>
                    <p><span class="font-bold">ID</span>: {{ user.id }}</p>
                    <p><span class="font-bold">Name</span>: {{ user.name }}</p>
                    <p><span class="font-bold">Email</span>: {{ user.email }}</p>
                    <p><span class="font-bold">Phone</span>: {{ user.phone }}</p>
                    <p><span class="font-bold">Position</span>: {{ user.position }}</p>
                    <p><span class="font-bold">Position ID</span>: {{ user.position_id }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="m-4 p-4 border rounded-md shadow-md h-fit">
        <h2 class="text-xl font-bold mb-4">Create User 🐣</h2>
        <form @submit.prevent="postUser">
            <div class="mb-2">
                <label class="block">Name:</label>
                <input v-model="form.name" class="border p-2 w-full" />
            </div>
            <div class="mb-2">
                <label class="block">Email:</label>
                <input v-model="form.email" class="border p-2 w-full" />
            </div>
            <div class="mb-2">
                <label class="block">Phone:</label>
                <input v-model="form.phone" class="border p-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block">Position:</label>
                <select v-if="positions" v-model="selectedPos" class="p-2 w-full">
                    <option v-for="pos in positions" :value="pos.name">{{ pos.name }}</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block">Photo:</label>
                <input id="photoInput" type="file" @change="handlePhotoUpload($event)" class="py-2 w-full" />
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        </form>
        <div v-if="postResponse" class="mt-4">
            <div :class="postResponse.data.success ? 'text-green-600' : 'text-red-600'">{{ postResponse.data.message }}</div>
            <div v-if="postResponse.data.fails" v-for="fail in postResponse.data.fails">
                <div class="text-red-600">{{ fail[0] }}</div>
            </div>
        </div>
    </div>
</template>
