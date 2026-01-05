<template>
  <SideNavLayout>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title">Members Management</h3>

              <Link
                v-if="canCreateMember"
                :href="route('members.create')"
                class="btn btn-outline-primary"
              >
                <i class="fa fa-plus me-1"></i> Create Member
              </Link>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped align-middle">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Full Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Join Date</th>
                      <th>Status</th>
                      <th v-if="canEditMember || canDeleteMember">Actions</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="member in membersData" :key="member.id">
                      <td>{{ member.id }}</td>
                      <td>
                        {{ member.first_name }} {{ member.last_name }}
                      </td>
                      <td>{{ member.email }}</td>
                      <td>{{ member.phone }}</td>
                      <td>{{ formatDate(member.join_date) }}</td>
                      <td>
                        <span
                          class="badge"
                          :class="member.status === 'active' ? 'bg-success' : 'bg-secondary'"
                        >
                          {{ member.status }}
                        </span>
                      </td>

                      <td v-if="canEditMember || canDeleteMember">
                        <div class="btn-group">
                          <Link
                            v-if="canEditMember"
                            :href="route('members.edit', member.id)"
                            class="btn btn-sm btn-outline-primary me-1"
                          >
                            <i class="fa fa-edit"></i>
                          </Link>

                          <button
                            v-if="canDeleteMember"
                            class="btn btn-sm btn-outline-danger"
                            @click="deleteResource(member)"
                          >
                            <i class="fa fa-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>

                    <tr v-if="membersData.length === 0">
                      <td
                        :colspan="canEditMember || canDeleteMember ? 7 : 6"
                        class="text-center text-muted py-4"
                      >
                        No members found.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div
                v-if="shouldShowPagination"
                class="d-flex justify-content-between align-items-center mt-4"
              >
                <small class="text-muted">{{ paginationInfo.showing }}</small>

                <nav>
                  <ul class="pagination">
                    <li class="page-item" :class="{ disabled: isFirstPage }">
                      <button class="page-link" @click="goToPage(1)" :disabled="isFirstPage">
                        <i class="fa fa-angle-double-left"></i>
                      </button>
                    </li>

                    <li class="page-item" :class="{ disabled: !canGoPrev }">
                      <button
                        class="page-link"
                        @click="goToPage(pagination.current_page - 1)"
                        :disabled="!canGoPrev"
                      >
                        <i class="fa fa-angle-left"></i>
                      </button>
                    </li>

                    <li
                      v-for="page in visiblePages"
                      :key="page"
                      class="page-item"
                      :class="{ active: page === pagination.current_page, disabled: page === '...' }"
                    >
                      <button
                        v-if="page !== '...'"
                        class="page-link"
                        @click="goToPage(page)"
                      >
                        {{ page }}
                      </button>
                      <span v-else class="page-link">{{ page }}</span>
                    </li>

                    <li class="page-item" :class="{ disabled: !canGoNext }">
                      <button
                        class="page-link"
                        @click="goToPage(pagination.current_page + 1)"
                        :disabled="!canGoNext"
                      >
                        <i class="fa fa-angle-right"></i>
                      </button>
                    </li>

                    <li class="page-item" :class="{ disabled: isLastPage }">
                      <button
                        class="page-link"
                        @click="goToPage(pagination.last_page)"
                        :disabled="isLastPage"
                      >
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

    <!-- Confirm Delete -->
    <ConfirmDelete
      :isOpen="showDeleteModal"
      :title="resourceTitle"
      message="Are you sure you want to delete this member?"
      @close="showDeleteModal = false"
      @confirm="confirmDelete"
    />
  </SideNavLayout>
</template>

<script setup>
import { defineProps, computed, ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import SideNavLayout from '@/Layout/SideNavLayout.vue'
import ConfirmDelete from '@/Components/Helper/ConfirmDelete.vue'
import { createToaster } from '@meforma/vue-toaster'
import { usePagination } from '@/utilities/pagination.js'

const page = usePage()
const permissions = computed(() => page.props.can || [])

const toaster = createToaster({ duration: 4000 })

const props = defineProps({
  members: {
    type: [Array, Object],
    default: () => []
  }
})

const membersData = computed(() => {
  if (Array.isArray(props.members)) return props.members
  if (props.members?.data) return props.members.data
  return []
})

const pagination = computed(() => props.members)
const filters = computed(() => ({}))

const {
  goToPage,
  visiblePages,
  shouldShowPagination,
  paginationInfo,
  canGoPrev,
  canGoNext,
  isFirstPage,
  isLastPage
} = usePagination(pagination, filters, 'members.index')

const canCreateMember = computed(() => permissions.value.includes('create-member'))
const canEditMember = computed(() => permissions.value.includes('edit-member'))
const canDeleteMember = computed(() => permissions.value.includes('delete-member'))

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

/* Delete logic */
const showDeleteModal = ref(false)
const resourceToDelete = ref(null)
const resourceTitle = ref('')

const deleteResource = (member) => {
  resourceToDelete.value = member.id
  resourceTitle.value = `Delete member "${member.first_name} ${member.last_name}"`
  showDeleteModal.value = true
}

const confirmDelete = () => {
  router.delete(route('members.destroy', resourceToDelete.value), {
    onSuccess: () => {
      toaster.success(page.props.flash.message)
      router.get(route('members.index'))
    },
    onError: () => {
      toaster.error('Failed to delete member')
    }
  })
  showDeleteModal.value = false
}
</script>

<style scoped>
.pagination {
  gap: 0.5rem;
}

.pagination .page-link {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
  background-color: #f8f9fa;
  font-weight: 600;
}

.pagination .page-item.active .page-link {
  background-color: #333;
  color: #fff;
}
</style>
