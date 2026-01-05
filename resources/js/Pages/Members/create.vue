<template>
  <SideNavLayout>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-header mb-3">
            <h1 class="page-title">Create Member</h1>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Member Information</h5>
            </div>

            <div class="card-body">
              <form @submit.prevent="submit">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">First Name *</label>
                    <input
                      type="text"
                      v-model="form.first_name"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.first_name }"
                    />
                    <div class="invalid-feedback">
                      {{ form.errors.first_name }}
                    </div>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Last Name</label>
                    <input
                      type="text"
                      v-model="form.last_name"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.last_name }"
                    />
                    <div class="invalid-feedback">
                      {{ form.errors.last_name }}
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Email *</label>
                  <input
                    type="email"
                    v-model="form.email"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.email }"
                  />
                  <div class="invalid-feedback">
                    {{ form.errors.email }}
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Phone</label>
                  <input
                    type="text"
                    v-model="form.phone"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.phone }"
                  />
                  <div class="invalid-feedback">
                    {{ form.errors.phone }}
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Join Date *</label>
                  <input
                    type="date"
                    v-model="form.join_date"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.join_date }"
                  />
                  <div class="invalid-feedback">
                    {{ form.errors.join_date }}
                  </div>
                </div>

                <div class="mb-4">
                  <div class="form-check">
                    <input
                      type="checkbox"
                      v-model="form.status"
                      true-value="active"
                      false-value="inactive"
                      class="form-check-input"
                      id="status"
                    />
                    <label for="status" class="form-check-label fw-bold">
                      Active Member
                    </label>
                  </div>
                </div>

                <div class="d-flex justify-content-between">
                  <button
                    type="submit"
                    class="btn btn-outline-primary"
                    :disabled="form.processing"
                  >
                    <i class="fa fa-save me-1"></i>
                    {{ form.processing ? 'Saving...' : 'Create Member' }}
                  </button>

                  <Link
                    :href="route('members.index')"
                    class="btn btn-outline-secondary"
                  >
                    Back to Members
                  </Link>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Side Info -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Guidelines</h5>
            </div>
            <div class="card-body">
              <ul class="list-unstyled small text-muted">
                <li>• First name and email are required</li>
                <li>• Email must be unique</li>
                <li>• Join date should reflect registration date</li>
                <li>• Inactive members won’t appear in active lists</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </SideNavLayout>
</template>

<script setup>
import { useForm, Link, router, usePage } from '@inertiajs/vue3'
import SideNavLayout from '@/Layout/SideNavLayout.vue'
import { createToaster } from '@meforma/vue-toaster'

const page = usePage()
const toaster = createToaster({ duration: 4000 })

const form = useForm({
  branch_id: null,
  tier_id: null,
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  join_date: '',
  status: 'active'
})

const submit = () => {
  form.post(route('members.store'), {
    onSuccess: () => {
      toaster.success(page.props.flash.message)
      router.get(route('members.index'))
    },
    onError: () => {
      toaster.error('Failed to create member')
    }
  })
}
</script>
