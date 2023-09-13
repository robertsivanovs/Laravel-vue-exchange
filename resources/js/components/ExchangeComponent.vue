<template>
    <div class="card-body">
      <div>
        <span class="label">1 EUR to</span>
        <select id="currencySelect" v-model="selectedCurrency">
          <option value="">Select a currency</option>
          <option v-for="(value, key) in newRates" :key="key" :value="value">{{ key }}</option>
        </select>
        <span class="label">Exchange Rate</span>

        <div v-if="selectedCurrency">
          <span class="label">Last updated: {{ selectedCurrency.last_updated }}</span>
          <br><br>
          <!-- Pagination controls -->
          <button @click="previousPage" :disabled="currentPage === 1">Previous</button>
          <span>{{ currentPage }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
          
          <table>
            <tr>
              <th class="sort" @click="sortByDate">Date ^</th>
              <th>EUR TO </th>
            </tr>
            <tr v-for="(rate) in paginatedRates">
                <td>{{ rate[0] }} </td>  
                <td>{{ rate[1] }} </td>
            </tr>
          </table> 
          <!-- Pagination controls -->
          <button @click="previousPage" :disabled="currentPage === 1">Previous</button>
          <span>{{ currentPage }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>

          <br><br>
          <span class="label">Minimum: {{ selectedCurrency.lowest_rate }}</span>
          <br><br>
          <span class="label">Maximum: {{ selectedCurrency.highest_rate }}</span>
          <br><br>
          <span class="label">Average: {{ selectedCurrency.average_rate }}</span>

        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['newRates'],
    data() {
      return {
        selectedCurrency: null,
        currentPage: 1,
        itemsPerPage: 5, // Change this to the desired number of items per page
        sortByAscending: true
      };
    },
    computed: {
      paginatedRates() {
        if (!this.selectedCurrency) return [];
        const rates = this.selectedCurrency.exchange_rates;
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;

        if (this.sortByAscending) {
          return Object.entries(rates).slice(start, end).reverse();
        }
        
        return Object.entries(rates).slice(start, end);

      },
      totalPages() {
        if (!this.selectedCurrency) return 0;
        const ratesCount = Object.keys(this.selectedCurrency.exchange_rates).length;
        return Math.ceil(ratesCount / this.itemsPerPage);
      },
    },
    methods: {
      previousPage() {
        if (this.currentPage > 1) {
          this.currentPage--;
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.currentPage++;
        }
      },
      sortByDate() {
        this.sortByAscending = !this.sortByAscending;
      }
    },
  };
  </script>
  