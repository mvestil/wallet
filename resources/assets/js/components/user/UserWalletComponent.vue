<template>
    <div class="row">
        <div class="col-md-4">
            <h4>My Wallet - {{ email }}</h4>
            <div v-if='walletInfo.length !== 0'>
                <p>Balance : {{ walletInfo.balance }}</p>
                <div>
                    <ul class="list-group">
                        <span v-if='walletInfo.transactions.length === 0'>No transaction</span>
                        <span v-else>Recent Transactions<br/><br/></span>
                        <li class="list-group-item" v-for="transaction in walletInfo.transactions">
                            {{ transaction.amount }} <strong>{{ transaction.type }}</strong>
                            <span v-if="transaction.remarks">(Remarks: {{ transaction.remarks }})</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h4>Credit</h4>
            <form action="#" @submit.prevent="creditWallet()">
                <div class="form-group">
                    <input v-model="credit.amount" type="text" class="form-control" autofocus placeholder="Enter credit amount">
                </div>
                <div class="form-group">
                    <label >Remarks</label>
                    <textarea v-model="credit.remarks" class="form-control" cols="20" rows="5" placeholder="Enter credit remarks"></textarea>
                </div>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Credit</button>
                </span>
            </form>

            <hr>

            <h4>Debit</h4>
            <form action="#" @submit.prevent="debitWallet()">
                <div class="form-group">
                    <input v-model="debit.amount" type="text" name="body" class="form-control" placeholder="Enter debit amount">
                </div>
                <div class="form-group">
                    <textarea v-model="debit.remarks" class="form-control" cols="20" rows="5" placeholder="Enter debit remarks"></textarea>
                </div>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Debit</button>
                </span>
            </form>
        </div>
    </div>
</template>
<script>

    export default {
        props: ['email'],
        data() {
            return {
                walletInfo : [],
                debit : {
                    amount : '',
                    remarks : ''
                },
                credit : {
                    amount : '',
                    remarks : ''
                }
            };
        },

        created() {
            this.getWallet()
        },

        methods: {
            getWallet() {
                axios.get('/api/wallets/' +  this.email)
                    .then((res) => {
                        this.walletInfo = res.data.data;
                    })
                    .catch((err) => {
                        this.walletInfo = [];
                    });
            },

            debitWallet() {
                axios.delete('/api/wallets/' + this.email + '/transact', { 'data' : this.debit})
                    .then((res) => {
                        this.debit.amount = '';
                        this.debit.remarks = '';
                        this.getWallet();
                    })
                    .catch((err) => alert(err.response.data.message));
            },

            creditWallet() {
                axios.put('/api/wallets/' + this.email + '/transact', this.credit)
                    .then((res) => {
                        this.credit.amount = '';
                        this.credit.remarks = '';
                        this.getWallet();
                    })
                    .catch((err) => alert(err.response.data.message));
            },
        }
    }
</script>