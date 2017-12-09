<template>
    <div class='row'>
        <div class="col-md-4">
            <h4>New Wallet</h4>
            <form action="#" @submit.prevent="createWallet()">
                <div class="input-group">
                    <input v-model="wallet.email" type="text" name="body" class="form-control" autofocus placeholder="Email address">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">New Wallet</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <h4>All Wallets</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            Email
                        </th>
                        <th>
                            Balance
                        </th>
                        <th>
                            Recent Transactions
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if='list.length === 0'><td colspan="4">There are no wallets yet. Start creating one!</td></tr>
                    <tr v-for="(wallet, index) in list">
                        <td>{{ wallet.email }}</td>
                        <td>{{ wallet.balance }}</td>
                        <td>
                            <ul class="list-group">
                                <span v-if='wallet.transactions.length === 0'>No transaction</span>
                                <li class="list-group-item" v-for="transaction in wallet.transactions">
                                    {{ transaction.amount }} <strong>{{ transaction.type }}</strong>
                                    <span v-if="transaction.remarks">(Remarks: {{ transaction.remarks }})</span>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align:center">
                            <a :href="'/user/' + wallet.email" target="_blank" class="btn btn-info btn-xs ">Login</a>
                            <button @click="deleteWallet(wallet.email)" class="btn btn-danger btn-xs">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-4">
                <h4>Search Wallet</h4>
                <form action="#" @submit.prevent="searchWallet(search.email)">
                    <div class="input-group">
                        <input v-model="search.email" type="text" class="form-control" placeholder="Email address">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <h4>Search Result</h4>
                <div v-if='walletInfo.length === 0'>No result</div>
                <div v-if='walletInfo.length !== 0'>
                    <p>Email : {{ walletInfo.email }}</p>
                    <p>Balance : {{ walletInfo.balance }}</p>
                    <div>
                        <ul class="list-group">
                            <span v-if='walletInfo.transactions.length === 0'>No transaction</span>
                            <span v-else>Recent Transactions</span>
                            <li class="list-group-item" v-for="transaction in walletInfo.transactions">
                                {{ transaction.amount }} <strong>{{ transaction.type }}</strong>
                                <span v-if="transaction.remarks">(Remarks: {{ transaction.remarks }})</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    // hardcoded api token for admin - assuming already authenticated
    axios.defaults.headers.common['x-auth-key'] = 'Zioj23D92j2kGf9D' // for all requests

    export default {
        data() {
            return {
                list: [],
                wallet: {
                    email: ''
                },
                search: {
                    email: ''
                },
                walletInfo : []
            };
        },

        created() {
            this.fetchWalletList();
        },

        methods: {

            fetchWalletList() {
                axios.get('api/admin/wallets').then((res) => {
                    this.list = res.data.data;
                });
            },

            createWallet() {
                axios.post('api/admin/wallets', this.wallet)
                    .then((res) => {
                        this.fetchWalletList();
                    })
                    .catch((err) => alert(err.response.data.message));
            },

            deleteWallet(email) {
                axios.delete('api/admin/wallets/' + email)
                    .then((res) => {
                        this.fetchWalletList()
                    })
                    .catch((err) => alert(err.response.data.message));
            },

            searchWallet(email) {
                axios.get('api/admin/wallets/' +  email)
                    .then((res) => {
                        this.walletInfo = res.data.data;
                    })
                    .catch((err) => {
                        this.walletInfo = [];
                    });
            },
        }
    }
</script>