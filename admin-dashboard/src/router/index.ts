import {createRouter, createWebHashHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AboutView from "@/views/AboutView.vue";
import CookieManager from "@/views/CookieManager.vue";
import CookieManagerCategory from "@/views/CookieManagerCategory.vue";
import CookieManagerCategoryOverview from "@/views/CookieManagerCategoryOverview.vue";
import CookieManagerCategoryVendors from "@/views/CookieManagerCategoryVendors.vue";
import CookieManagerCategoryVendorsNew from "@/views/CookieManagerCategoryVendorsNew.vue";
import CookieManagerCategories from "@/views/CookieManagerCategories.vue";
import {ref} from "vue";
import CookieManagerCategoryVendorsIndex from "@/views/CookieManagerCategoryVendorsIndex.vue";
import CookieManagerCategoryVendorsEdit from "@/views/CookieManagerCategoryVendorsEdit.vue";
import CookieManagerCategoryNew from "@/views/CookieManagerCategoryNew.vue";
import Settings from "@/views/Settings.vue";
import CustomizationSettings from "@/views/CustomizationSettings.vue";

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/about',
            name: 'about',
            component: AboutView
        },
        {
            path: '/content',
            name: 'content',
            component: CustomizationSettings,
        },
        {
            path: '/settings',
            name: 'settings',
            component: Settings,
        },
        {
            path: '/manager',
            name: 'manager',
            component: CookieManager,
            meta: {
                title: 'Categories',
                bc: 'Categories',
            },
            redirect: {name: "manager-categories"},
            children: [
                {
                    path: '',
                    name: 'manager-categories',
                    component: CookieManagerCategories,
                    meta: {
                        title: 'Cookies Categories',
                    },
                },
                {
                    path: '',
                    name: 'manager-categories-new',
                    component: CookieManagerCategoryNew,
                    meta: {
                        title: 'New Category',
                        bc: 'New Category',
                    },
                },
                {
                    path: ':categoryId',
                    name: 'manager-category',
                    component: CookieManagerCategory,
                    props: true,
                    meta: {
                        title: ref(),
                        bc: ref(),
                    },
                    redirect: {name: "manager-category-overview"},
                    children: [
                        {
                            path: '',
                            name: 'manager-category-overview',
                            component: CookieManagerCategoryOverview,
                        },
                        {
                            path: 'vendors',
                            name: 'manager-category-vendors',
                            component: CookieManagerCategoryVendors,
                            meta: {
                                title: 'Vendors',
                                bc: 'Vendors',
                            },
                            redirect: {name: "manager-category-vendors-index"},
                            children: [
                                {
                                    path: '',
                                    name: 'manager-category-vendors-index',
                                    component: CookieManagerCategoryVendorsIndex,
                                },
                                {
                                    path: 'new',
                                    name: 'manager-category-vendors-new',
                                    component: CookieManagerCategoryVendorsNew,
                                    meta: {
                                        title: 'New',
                                        bc: 'New',
                                    },
                                },
                                {
                                    path: ':vendorId',
                                    name: 'manager-category-vendors-edit',
                                    component: CookieManagerCategoryVendorsEdit,
                                    props: true,
                                    meta: {
                                        title: ref(),
                                        bc: ref(),
                                    },
                                },
                            ]
                        },
                    ]
                },
            ]
        }
    ]
});

export default router
