const getInstagramUsers = async () => await yiiFetch(instagramUsersListUrl);
const getInstagramUserPosts = async id => await yiiFetch(instagramUserPostsUrl, {id: id}, 'GET', {});
const addInstagramUser = async username => await yiiFetch(instagramUsersAddUrl, {}, 'POST', {username: username});
const removeInstagramUser = async id => await yiiFetch(instagramUsersRemoveUrl, {id: id}, 'DELETE', {});

const app = new Vue({
    el: '#app',
    data: {
        users: [],
        username: '',
    },
    async created() {
        this.users = await (await getInstagramUsers()).json();
    },
    methods: {
        async addUser() {
            if (this.users.find(u => u.name === this.username))
                alert('Пользователь уже добавлен!');
            else {
                const user = await (await addInstagramUser(this.username)).json();
                this.users.push(user);
            }

            this.username = '';
        },
        async removeUser(id) {
            await removeInstagramUser(id);
            this.users.splice(this.users.findIndex(u => u.id === id), 1);
        },
    },
});

setInterval(async () => {
    app.users = await (await getInstagramUsers()).json();
}, 1000 * 60 * 10);
