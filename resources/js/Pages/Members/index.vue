<template>
  <SideNavLayout>
    <div class="container-fluid">
      <div class="card admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Members Management</h3>
        </div>

        <div class="card-body">
          <div class="table-responsive admin-table">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Join Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="member in membersData" :key="member.member_id">
                  <td>{{ member.member_id }}</td>
                  <td>{{ member.first_name }} {{ member.last_name }}</td>
                  <td>{{ member.email }}</td>
                  <td>{{ member.phone }}</td>
                  <td>{{ formatDate(member.join_date) }}</td>
                  <td>
                    <span :class="member.status === 'Active' ? 'badge bg-success' : 'badge bg-secondary'">
                      {{ member.status }}
                    </span>
                  </td>
                </tr>

                <tr v-if="membersData.length === 0">
                  <td colspan="6" class="text-center text-muted py-4">No members found.</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav v-if="pagination" class="mt-3">
            <ul class="pagination">
              <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
                <button class="page-link" @click="goTo(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">Prev</button>
              </li>
              <li class="page-item disabled">
                <span class="page-link">Page {{ pagination.current_page }} / {{ pagination.last_page }}</span>
              </li>
              <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
                <button class="page-link" @click="goTo(pagination.next_page_url)" :disabled="!pagination.next_page_url">Next</button>
              </li>
            </ul>
          </nav>

        </div>
      </div>
    </div>
  </SideNavLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import SideNavLayout from '@/Layout/SideNavLayout.vue';

const props = defineProps({
  members: {
    type: [Array, Object],
    default: () => []
  }
});

const membersData = computed(() => {
  if (Array.isArray(props.members)) return props.members;
  return props.members?.data || [];
});

const pagination = computed(() => {
  if (!props.members || Array.isArray(props.members)) return null;
  return {
    current_page: props.members.current_page,
    last_page: props.members.last_page,
    prev_page_url: props.members.prev_page_url,
    next_page_url: props.members.next_page_url
  };
});

function formatDate(dt) {
  if (!dt) return '';
  return new Date(dt).toLocaleDateString();
}

function goTo(url) {
  if (!url) return;
  // gunakan Inertia router untuk load page pagination
  router.get(url, {}, { preserveState: true, replace: true });
}
</script>

<style scoped>
/* copy gaya dari Users/Index.vue agar konsisten */
</style>