<template>
  <div class="currency-exchange-widget">
    <div class="exchange-widget-container">
      <span class="widget-label heading">1 EUR to</span>
      <select id="currencySelect" v-model="selectedCurrency" class="currency-select">
        <option value="">Select a currency</option>
        <option v-for="(value, key) in newRates" :key="key" :value="value">{{ key }}</option>
      </select>
      <span class="widget-label heading">Exchange Rate</span>

      <div v-if="selectedCurrency" class="currency-details">
        <p class="widget-label">Last updated: {{ selectedCurrency.last_updated }}</p>

        <!-- Pagination controls -->
        <span class="pagination-button" @click="previousPage" :disabled="currentPage === 1">&lt;</span>
        <span class="current-page">{{ currentPage }}</span>
        <span class="pagination-button" @click="nextPage" :disabled="currentPage === totalPages">&gt;</span>

        <table class="exchange-rate-table">
          <tr>
            <th class="sort" @click="sortByDate">Date ^</th>
            <th>EUR to {{ selectedCurrency.quote_currency }}</th>
          </tr>
          <tr v-for="(rate) in paginatedRates">
            <td>{{ rate[0] }} </td>  
            <td>{{ rate[1] }} </td>
          </tr>
        </table> 
        <!-- Pagination controls -->
        <span class="pagination-button" @click="previousPage" :disabled="currentPage === 1">&lt;</span>
        <span class="current-page">{{ currentPage }}</span>
        <span class="pagination-button" @click="nextPage" :disabled="currentPage === totalPages">&gt;</span>

        <p class="min-max-avg-labels"><span class="widget-label">Minimum: {{ selectedCurrency.lowest_rate }} {{ selectedCurrency.quote_currency }},</span>
          <span class="widget-label">Maximum: {{ selectedCurrency.highest_rate }} {{ selectedCurrency.quote_currency }}</span>
        </p>
        <span class="widget-label">Average: {{ selectedCurrency.average_rate }} {{ selectedCurrency.quote_currency }}</span>
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
        itemsPerPage: 1, // Change this to the desired number of items per page
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
    }
  };
  </script>
  