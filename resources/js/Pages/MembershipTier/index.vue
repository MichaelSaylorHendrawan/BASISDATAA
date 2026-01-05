<template>
  <SideNavLayout>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title">Products Management</h3>
              <Link v-if="canCreateProduct" :href="route('products.create')" class="btn btn-outline-primary">
                <i class="fa fa-plus me-1"></i> Create Product
              </Link>
            </div>
            <div class="card-body">
              <div class="table-responsive admin-table">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>SKU</th>
                      <th>Price</th>
                      <th>Stock</th>
                      <th>Created At</th>
                      <th v-if="canEditProduct || canDeleteProduct">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="product in productsData" :key="product.id">
                      <td>{{ product.id }}</td>
                      <td>{{ product.name }}</td>
                      <td>
                        <span class="badge bg-secondary">{{ product.sku }}</span>
                      </td>
                      <td>
                        <span class="text-success fw-bold">${{ formatPrice(product.price) }}</span>
                      </td>
                      <td>
                        <span 
                          :class="getStockBadgeClass(product.stock)"
                          class="badge"
                        >
                          {{ product.stock }} units
                        </span>
                      </td>
                      <td>{{ formatDate(product.created_at) }}</td>
                      <td v-if="canEditProduct || canDeleteProduct" class="edit-delete-container">
                        <div class="btn-group" role="group">
                          <Link v-if="canEditProduct" :href="route('products.edit', product.id)" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fa fa-edit"></i>
                          </Link>
                          <button v-if="canDeleteProduct" @click="deleteResource(product.id)" class="btn btn-sm btn-outline-danger">
                            <i class="fa fa-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr v-if="productsData.length === 0">
                      <td :colspan="canEditProduct || canDeleteProduct ? 7 : 6" class="text-center text-muted py-4">
                        No products found.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Pagination -->
              <div v-if="shouldShowPagination" class="d-flex justify-content-between align-items-center mt-4">
                <div class="pagination-info">
                  <small class="text-muted">{{ paginationInfo.showing }}</small>
                </div>
                <nav aria-label="Page navigation">
                  <ul class="pagination">
                    <!-- First Page -->
                    <li class="page-item" :class="{ disabled: isFirstPage }">
                      <button class="page-link" @click="goToPage(1)" :disabled="isFirstPage">
                        <i class="fa fa-angle-double-left"></i>
                      </button>
                    </li>
                    
                    <!-- Previous Page -->
                    <li class="page-item" :class="{ disabled: !canGoPrev }">
                      <button class="page-link" @click="goToPage(paginationData.current_page - 1)" :disabled="!canGoPrev">
                        <i class="fa fa-angle-left"></i>
                      </button>
                    </li>
                    
                    <!-- Page Numbers -->
                    <li v-for="page in visiblePages" :key="page" class="page-item" 
                        :class="{ 
                          active: page === paginationData.current_page,
                          disabled: page === '...'
                        }">
                      <button v-if="page !== '...'" class="page-link" @click="goToPage(page)">
                        {{ page }}
                      </button>
                      <span v-else class="page-link">{{ page }}</span>
                    </li>
                    
                    <!-- Next Page -->
                    <li class="page-item" :class="{ disabled: !canGoNext }">
                      <button class="page-link" @click="goToPage(paginationData.current_page + 1)" :disabled="!canGoNext">
                        <i class="fa fa-angle-right"></i>
                      </button>
                    </li>
                    
                    <!-- Last Page -->
                    <li class="page-item" :class="{ disabled: isLastPage }">
                      <button class="page-link" @click="goToPage(paginationData.last_page)" :disabled="isLastPage">
                        <i class="fa fa-angle-double-right"></i>
                      </button>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Confirm Dialog Component -->
    <ConfirmDelete :isOpen="showDeleteModal"
                   :title="resourceTitle"
                   message="Are you sure you want to delete this product?"
                   @close="showDeleteModal = false"
                   @confirm="confirmDelete"/>    
  </SideNavLayout>
</template>

<script setup>
import { defineProps, computed, ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import SideNavLayout from '@/Layout/SideNavLayout.vue';
import ConfirmDelete from "@/Components/Helper/ConfirmDelete.vue";
import { createToaster } from "@meforma/vue-toaster";
import { usePagination } from '@/utilities/pagination.js';

const page = usePage();
const permissions = computed(() => page.props.can || []);
const toaster = createToaster({
  position: "bottom",
  maxToasts: 5,
  duration: 4000
});

const props = defineProps({
  products: {
    type: [Array, Object],
    default: () => []
  }
});

// Handle both paginated and non-paginated data
const productsData = computed(() => {
  if (Array.isArray(props.products)) {
    return props.products;
  } else if (props.products?.data) {
    return props.products.data;
  }
  return [];
});

// Pagination setup
const paginationData = computed(() => props.products);
const currentFilters = computed(() => ({}));
const {
  goToPage,
  visiblePages,
  shouldShowPagination,
  paginationInfo,
  canGoPrev,
  canGoNext,
  isFirstPage,
  isLastPage
} = usePagination(paginationData, currentFilters, 'products.index');

// Permission checks
const canCreateProduct = computed(() => permissions.value.includes('create-product'));
const canEditProduct = computed(() => permissions.value.includes('edit-product'));
const canDeleteProduct = computed(() => permissions.value.includes('delete-product'));

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString();
};

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2);
};

const getStockBadgeClass = (stock) => {
  if (stock === 0) {
    return 'bg-danger';
  } else if (stock < 10) {
    return 'bg-warning';
  } else {
    return 'bg-success';
  }
};

// confirm delete modal
const showDeleteModal = ref(false)
const resourceToDelete = ref(null)
const resourceTitle = ref(null);

const deleteResource = (id) => {
    resourceToDelete.value = id
    showDeleteModal.value = true;
    resourceTitle.value = `Delete product "${productsData.value.find(item => item.id === id).name}"`

    // Small delay to trigger animation
    setTimeout(() => {
        document.querySelector(".modal-content").classList.add("show");
    }, 10);
};

const confirmDelete = () => {
    router.delete(route('products.destroy', resourceToDelete.value), {
        onSuccess:()=>{
            if(page.props.flash.status===true){
                toaster.success(page.props.flash.message);
                router.get(route('products.index'))
            }
            else if(page.props.flash.status===false){
                toaster.error(page.props.flash.message);
            }
            else {
                toaster.error(page.props.flash.message);
            }
        }
    })
    showDeleteModal.value = false
}
</script>

<style scoped>
.card {
  margin-bottom: 20px;
}

.badge {
  font-size: 0.875em;
}

.fw-bold {
  font-weight: bold;
}

/* Pagination Styles */
.pagination {
    gap: 0.5rem;
}

.pagination .page-item .page-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #333;
    border: none;
    background-color: #f8f9fa;
}

.pagination .page-item.active .page-link {
    background-color: #333;
    color: white;
}

.pagination .page-item.disabled .page-link {
    background-color: #e9ecef;
    color: #6c757d;
}

.pagination-info {
    font-size: 0.875rem;
}
</style>
