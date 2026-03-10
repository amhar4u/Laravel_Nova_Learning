<template>
  <div>
    <Head :title="pageTitle" />

    <!-- ═══════════════════════════════════════════════════════════
         PAGE HEADER
    ════════════════════════════════════════════════════════════════ -->
    <div class="flex items-center justify-between mb-6">
      <Heading>{{ pageTitle }}</Heading>

      <button v-if="view === 'list'" @click="showCreateForm"
        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700
               text-white text-sm font-medium rounded-lg transition-colors">
        + Create Post
      </button>

      <button v-else @click="view = 'list'"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800
               dark:text-gray-400 dark:hover:text-white transition-colors">
        ← Back to List
      </button>
    </div>

    <!-- Flash Messages -->
    <transition name="fade">
      <div v-if="flash.success"
        class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ flash.success }}
      </div>
    </transition>
    <transition name="fade">
      <div v-if="flash.error"
        class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ flash.error }}
      </div>
    </transition>

    <!-- ═══════════════════════════════════════════════════════════
         LIST VIEW
    ════════════════════════════════════════════════════════════════ -->
    <Card v-if="view === 'list'">
      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center p-12">
        <p class="text-gray-400 animate-pulse">Loading posts…</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="posts.length === 0"
        class="flex flex-col items-center justify-center p-12 text-center">
        <div class="text-5xl mb-4">📝</div>
        <p class="text-gray-500 text-lg font-medium">No posts yet</p>
        <p class="text-gray-400 text-sm mt-1">Click "Create Post" to add your first post.</p>
      </div>

      <!-- Posts Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full table-auto text-sm">
          <thead>
            <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs uppercase text-gray-500 tracking-wide">
              <th class="px-4 py-3 w-10">#</th>
              <th class="px-4 py-3">Title / Slug</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Category</th>
              <th class="px-4 py-3">Author</th>
              <th class="px-4 py-3 text-center">Featured</th>
              <th class="px-4 py-3 text-center">Rating</th>
              <th class="px-4 py-3">Created</th>
              <th class="px-4 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="post in posts" :key="post.id"
              class="border-t border-gray-100 dark:border-gray-700
                     hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
              <td class="px-4 py-3 text-gray-400 font-mono">{{ post.id }}</td>
              <td class="px-4 py-3">
                <p class="font-semibold dark:text-white">{{ post.title }}</p>
                <p class="text-gray-400 text-xs font-mono mt-0.5">{{ post.slug }}</p>
              </td>
              <td class="px-4 py-3">
                <span :class="statusClass(post.status)"
                  class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize">
                  {{ post.status }}
                </span>
              </td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-300 capitalize">
                {{ post.category || '—' }}
              </td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-300">
                {{ post.author || '—' }}
              </td>
              <td class="px-4 py-3 text-center">
                <span v-if="post.is_featured" class="text-yellow-500 font-bold text-base">★</span>
                <span v-else class="text-gray-300">—</span>
              </td>
              <td class="px-4 py-3 text-center text-gray-500">
                {{ post.rating != null ? post.rating : '—' }}
              </td>
              <td class="px-4 py-3 text-gray-400 text-xs">
                {{ formatDate(post.created_at) }}
              </td>
              <td class="px-4 py-3 text-right">
                <button @click="editPost(post)"
                  class="text-blue-500 hover:text-blue-700 font-medium mr-3">Edit</button>
                <button @click="deletePost(post.id)"
                  class="text-red-500 hover:text-red-700 font-medium">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Card>

    <!-- ═══════════════════════════════════════════════════════════
         CREATE / EDIT FORM
         Every section below demonstrates a different Nova field type
    ════════════════════════════════════════════════════════════════ -->
    <Card v-if="view === 'create' || view === 'edit'" class="p-0 overflow-hidden">
      <div class="p-6 border-b border-gray-100 dark:border-gray-700">
        <h2 class="text-lg font-semibold dark:text-white">
          {{ view === 'create' ? '✏️ Create New Post' : '✏️ Edit Post' }}
        </h2>
        <p class="text-gray-400 text-sm mt-1">
          Each field shows the equivalent Nova field type in parentheses.
        </p>
      </div>

      <form @submit.prevent="submitForm" class="divide-y divide-gray-100 dark:divide-gray-700">

        <!-- ─── SECTION: Basic Information ──────────────────────── -->
        <div class="p-6 space-y-4">
          <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">
            📝 Basic Information
          </h3>

          <!-- TEXT FIELD: Title (required) -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Title <span class="text-red-500">*</span>
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Text::make('Title')</code>)</span>
            </label>
            <input v-model="form.title" type="text" @input="autoSlug"
              placeholder="My Awesome Post"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300" />
            <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title[0] }}</p>
          </div>

          <!-- SLUG FIELD: auto-generated from title -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Slug <span class="text-red-500">*</span>
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Slug::make('Slug')-&gt;from('title')</code>)</span>
            </label>
            <input v-model="form.slug" type="text"
              placeholder="my-awesome-post"
              class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none
                     focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600
                     dark:text-white border-gray-300" />
            <p class="text-gray-400 text-xs mt-1">Auto-generated from the Title. You can edit it manually.</p>
            <p v-if="errors.slug" class="text-red-500 text-xs mt-1">{{ errors.slug[0] }}</p>
          </div>

          <!-- TEXT FIELD: Author -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Author
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Text::make('Author')</code>)</span>
            </label>
            <input v-model="form.author" type="text"
              placeholder="John Doe"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300" />
          </div>

          <!-- SELECT FIELD: Category -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Category
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Select::make('Category')-&gt;options([...])</code>)</span>
            </label>
            <select v-model="form.category"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300">
              <option value="">— Select a category —</option>
              <option value="technology">Technology</option>
              <option value="science">Science</option>
              <option value="health">Health &amp; Wellness</option>
              <option value="business">Business</option>
              <option value="arts">Arts &amp; Culture</option>
              <option value="sports">Sports</option>
              <option value="travel">Travel</option>
              <option value="food">Food &amp; Cooking</option>
            </select>
          </div>
        </div>

        <!-- ─── SECTION: Content ────────────────────────────────── -->
        <div class="p-6 space-y-4">
          <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">
            📄 Content
          </h3>

          <!-- TRIX / MARKDOWN FIELD: Content body -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Content (Body)
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Trix::make('Content')</code> or <code>Markdown::make('Content')</code>)</span>
            </label>
            <textarea v-model="form.content" rows="8"
              placeholder="Write the full post content here...&#10;Supports HTML / Markdown depending on the Nova field you choose."
              class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none
                     focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600
                     dark:text-white border-gray-300 resize-y">
            </textarea>
            <p v-if="errors.content" class="text-red-500 text-xs mt-1">{{ errors.content[0] }}</p>
          </div>

          <!-- TEXTAREA FIELD: Excerpt / Summary -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Excerpt (Short Summary)
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Textarea::make('Excerpt')</code>)</span>
            </label>
            <textarea v-model="form.excerpt" rows="3"
              placeholder="A brief summary shown in post listings and previews."
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300 resize-y">
            </textarea>
          </div>

          <!-- TAGS FIELD: Comma-separated -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Tags
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Tag::make('Tags')</code> — stored as comma-separated string)</span>
            </label>
            <input v-model="form.tags" type="text"
              placeholder="laravel, nova, php, tutorial, beginner"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300" />
            <p class="text-gray-400 text-xs mt-1">Separate each tag with a comma.</p>

            <!-- Tag preview chips -->
            <div v-if="tagList.length" class="flex flex-wrap gap-2 mt-2">
              <span v-for="tag in tagList" :key="tag"
                class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">
                {{ tag }}
              </span>
            </div>
          </div>
        </div>

        <!-- ─── SECTION: Publishing Settings ───────────────────── -->
        <div class="p-6 space-y-4">
          <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">
            🚀 Publishing Settings
          </h3>

          <!-- SELECT / BADGE FIELD: Status -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Status <span class="text-red-500">*</span>
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Select::make('Status')</code> or <code>Badge::make('Status')</code>)</span>
            </label>
            <select v-model="form.status"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300">
              <option value="draft">📝 Draft</option>
              <option value="published">✅ Published</option>
              <option value="archived">🗄️ Archived</option>
            </select>
            <!-- Live badge preview -->
            <div class="mt-2">
              <span :class="statusClass(form.status)"
                class="px-3 py-1 rounded-full text-xs font-semibold capitalize">
                {{ form.status }}
              </span>
              <span class="text-gray-400 text-xs ml-2">← Live badge preview</span>
            </div>
            <p v-if="errors.status" class="text-red-500 text-xs mt-1">{{ errors.status[0] }}</p>
          </div>

          <!-- DATE-TIME FIELD: Published At -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Published At
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>DateTime::make('Published At')</code>)</span>
            </label>
            <input v-model="form.published_at" type="datetime-local"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300" />
            <p class="text-gray-400 text-xs mt-1">Leave empty to not schedule a publish date.</p>
          </div>

          <!-- BOOLEAN FIELD: Is Featured -->
          <div class="flex items-start gap-3">
            <input v-model="form.is_featured" id="is_featured" type="checkbox"
              class="mt-0.5 w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" />
            <label for="is_featured" class="cursor-pointer">
              <p class="text-sm font-medium dark:text-gray-200">
                Featured Post
                <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Boolean::make('Is Featured')</code>)</span>
              </p>
              <p class="text-gray-400 text-xs mt-0.5">Featured posts are highlighted on the homepage.</p>
            </label>
          </div>
        </div>

        <!-- ─── SECTION: Media ──────────────────────────────────── -->
        <div class="p-6 space-y-4">
          <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">
            🖼️ Media
          </h3>

          <!-- IMAGE / FILE FIELD: Featured Image URL -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Featured Image URL
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Image::make('Featured Image')</code> or <code>File::make(...)</code>)</span>
            </label>
            <input v-model="form.featured_image" type="url"
              placeholder="https://images.unsplash.com/photo-..."
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300" />
            <p class="text-gray-400 text-xs mt-1">
              In real Nova: use <code>Image::make()</code> to upload files to storage.
              Here we store a URL for simplicity.
            </p>

            <!-- Image preview -->
            <div v-if="form.featured_image" class="mt-3">
              <img :src="form.featured_image" alt="Featured image preview"
                class="h-40 rounded-lg object-cover border border-gray-200 dark:border-gray-600"
                @error="form.featured_image = ''" />
              <p class="text-gray-400 text-xs mt-1">Image preview</p>
            </div>
          </div>
        </div>

        <!-- ─── SECTION: Numbers & Statistics ──────────────────── -->
        <div class="p-6 space-y-4">
          <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">
            📊 Numbers &amp; Statistics
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- NUMBER FIELD: Views (integer) -->
            <div>
              <label class="block text-sm font-medium dark:text-gray-200 mb-1">
                Views
                <span class="block text-xs text-gray-400 font-normal">(Nova: <code>Number::make('Views')</code>)</span>
              </label>
              <input v-model.number="form.views" type="number" min="0" placeholder="0"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                       focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                       border-gray-300" />
              <p class="text-gray-400 text-xs mt-1">Integer — min 0</p>
              <p v-if="errors.views" class="text-red-500 text-xs mt-1">{{ errors.views[0] }}</p>
            </div>

            <!-- NUMBER FIELD: Rating (decimal 0–10) -->
            <div>
              <label class="block text-sm font-medium dark:text-gray-200 mb-1">
                Rating (0–10)
                <span class="block text-xs text-gray-400 font-normal">(Nova: <code>Number::make('Rating')-&gt;step(0.1)</code>)</span>
              </label>
              <input v-model.number="form.rating" type="number" min="0" max="10" step="0.1" placeholder="8.5"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                       focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                       border-gray-300" />
              <p class="text-gray-400 text-xs mt-1">Decimal — 0.0 to 10.0</p>
              <p v-if="errors.rating" class="text-red-500 text-xs mt-1">{{ errors.rating[0] }}</p>
            </div>

            <!-- NUMBER FIELD: Reading Time (integer minutes) -->
            <div>
              <label class="block text-sm font-medium dark:text-gray-200 mb-1">
                Reading Time (min)
                <span class="block text-xs text-gray-400 font-normal">(Nova: <code>Number::make('Reading Time')</code>)</span>
              </label>
              <input v-model.number="form.reading_time" type="number" min="1" placeholder="5"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                       focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                       border-gray-300" />
              <p class="text-gray-400 text-xs mt-1">Integer — estimated minutes</p>
            </div>

          </div>
        </div>

        <!-- ─── SECTION: SEO Meta Fields ───────────────────────── -->
        <div class="p-6 space-y-4">
          <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">
            🔍 SEO Meta Fields
          </h3>

          <!-- TEXT FIELD: Meta Title -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Meta Title
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Text::make('Meta Title')</code>)</span>
            </label>
            <input v-model="form.meta_title" type="text" maxlength="255"
              placeholder="Override post title for search engines"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300" />
            <p class="text-gray-400 text-xs mt-1">{{ (form.meta_title || '').length }}/255 characters</p>
          </div>

          <!-- TEXTAREA FIELD: Meta Description -->
          <div>
            <label class="block text-sm font-medium dark:text-gray-200 mb-1">
              Meta Description
              <span class="ml-2 text-xs text-gray-400 font-normal">(Nova: <code>Textarea::make('Meta Description')</code>)</span>
            </label>
            <textarea v-model="form.meta_description" rows="3" maxlength="160"
              placeholder="Short description for search engines (max 160 characters)"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                     focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                     border-gray-300 resize-y">
            </textarea>
            <!-- Character counter changes colour at limit -->
            <p :class="(form.meta_description || '').length >= 160 ? 'text-red-500' : 'text-gray-400'"
              class="text-xs mt-1">
              {{ (form.meta_description || '').length }}/160 characters
            </p>
          </div>
        </div>

        <!-- ─── Form Actions ────────────────────────────────────── -->
        <div class="p-6 flex items-center gap-3 bg-gray-50 dark:bg-gray-800">
          <button type="submit" :disabled="submitting"
            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50
                   disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-colors">
            <span v-if="submitting">Saving…</span>
            <span v-else>{{ view === 'create' ? '✅ Create Post' : '💾 Update Post' }}</span>
          </button>
          <button type="button" @click="view = 'list'"
            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300
                   dark:hover:text-white transition-colors">
            Cancel
          </button>
        </div>

      </form>
    </Card>

  </div>
</template>

<script>
export default {
  // ─────────────────────────────────────────────────────────────
  // COMPONENT DATA
  // ─────────────────────────────────────────────────────────────
  data() {
    return {
      view:       'list',   // 'list' | 'create' | 'edit'
      posts:      [],
      loading:    false,
      submitting: false,
      editingId:  null,
      errors:     {},
      flash:      { success: '', error: '' },
      form:       this.emptyForm(),
    }
  },

  // ─────────────────────────────────────────────────────────────
  // COMPUTED
  // ─────────────────────────────────────────────────────────────
  computed: {
    pageTitle() {
      const map = { list: 'Posts', create: 'Create Post', edit: 'Edit Post' }
      return map[this.view] || 'Posts'
    },

    // Convert the comma-separated tags string into an array for the chip preview
    tagList() {
      if (!this.form.tags) return []
      return this.form.tags.split(',').map(t => t.trim()).filter(Boolean)
    },
  },

  // ─────────────────────────────────────────────────────────────
  // LIFECYCLE
  // ─────────────────────────────────────────────────────────────
  mounted() {
    this.fetchPosts()
  },

  // ─────────────────────────────────────────────────────────────
  // METHODS
  // ─────────────────────────────────────────────────────────────
  methods: {

    /** Returns a blank form object */
    emptyForm() {
      return {
        title:           '',
        slug:            '',
        author:          '',
        meta_title:      '',
        content:         '',
        excerpt:         '',
        meta_description:'',
        status:          'draft',
        category:        '',
        is_featured:     false,
        views:           0,
        rating:          null,
        reading_time:    null,
        published_at:    '',
        featured_image:  '',
        tags:            '',
      }
    },

    /** Auto-generate slug from title (only on Create) */
    autoSlug() {
      if (this.view === 'create') {
        this.form.slug = this.form.title
          .toLowerCase()
          .replace(/[^a-z0-9\s-]/g, '')
          .trim()
          .replace(/\s+/g, '-')
      }
    },

    /** Returns Tailwind classes for each status badge */
    statusClass(status) {
      const map = {
        draft:     'bg-yellow-100 text-yellow-800',
        published: 'bg-green-100 text-green-800',
        archived:  'bg-gray-200 text-gray-600',
      }
      return map[status] || 'bg-gray-100 text-gray-500'
    },

    formatDate(str) {
      if (!str) return '—'
      return new Date(str).toLocaleDateString(undefined, {
        year: 'numeric', month: 'short', day: 'numeric'
      })
    },

    /** Show a flash message and auto-clear it after 4 seconds */
    flash_(type, msg) {
      this.flash[type] = msg
      setTimeout(() => { this.flash[type] = '' }, 4000)
    },

    // ── API Calls ────────────────────────────────────────────────

    /** Fetch all posts from the backend */
    async fetchPosts() {
      this.loading = true
      try {
        const { data } = await Nova.request().get('/nova-vendor/post/posts')
        this.posts = data
      } catch {
        this.flash_('error', '❌ Failed to load posts. Check the console for details.')
      } finally {
        this.loading = false
      }
    },

    /** Open the Create form with a blank form */
    showCreateForm() {
      this.form      = this.emptyForm()
      this.errors    = {}
      this.editingId = null
      this.view      = 'create'
    },

    /** Load a post into the form for editing */
    editPost(post) {
      this.errors    = {}
      this.editingId = post.id
      this.form = {
        title:           post.title           || '',
        slug:            post.slug            || '',
        author:          post.author          || '',
        meta_title:      post.meta_title      || '',
        content:         post.content         || '',
        excerpt:         post.excerpt         || '',
        meta_description:post.meta_description|| '',
        status:          post.status          || 'draft',
        category:        post.category        || '',
        is_featured:     !!post.is_featured,
        views:           post.views           || 0,
        rating:          post.rating          ?? null,
        reading_time:    post.reading_time    ?? null,
        // Slice to 16 chars so <input type="datetime-local"> works
        published_at:    post.published_at ? post.published_at.slice(0, 16) : '',
        featured_image:  post.featured_image  || '',
        tags:            post.tags            || '',
      }
      this.view = 'edit'
    },

    /** Submit Create or Update */
    async submitForm() {
      this.submitting = true
      this.errors     = {}
      try {
        if (this.view === 'create') {
          await Nova.request().post('/nova-vendor/post/posts', this.form)
          this.flash_('success', '✅ Post created successfully!')
        } else {
          await Nova.request().put(`/nova-vendor/post/posts/${this.editingId}`, this.form)
          this.flash_('success', '💾 Post updated successfully!')
        }
        await this.fetchPosts()
        this.view = 'list'
      } catch (err) {
        if (err.response && err.response.status === 422) {
          // Laravel validation errors  →  { field: ['message', ...] }
          this.errors = err.response.data.errors || {}
          this.flash_('error', '⚠️ Please fix the validation errors highlighted below.')
        } else {
          this.flash_('error', '❌ Something went wrong. Please try again.')
        }
      } finally {
        this.submitting = false
      }
    },

    /** Delete a post after confirmation */
    async deletePost(id) {
      if (!confirm('Are you sure you want to delete this post? This cannot be undone.')) return
      try {
        await Nova.request().delete(`/nova-vendor/post/posts/${id}`)
        this.flash_('success', '🗑️ Post deleted successfully!')
        await this.fetchPosts()
      } catch {
        this.flash_('error', '❌ Failed to delete the post.')
      }
    },
  },
}
</script>

<style scoped>
/* Smooth fade for flash messages */
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to        { opacity: 0; }
</style>

