import { createStore } from 'vuex'

const store = createStore({
    state: {
        user: {},
        userToken: null,
        wallet: {},
        menus: [
            {id: 1, name: 'Панель управления', icon: 'pi pi-home', 'link': '/'},
            {id: 2, name: 'Транзакции', icon: 'pi pi-money-bill', 'link': '/transactions'},
        ],
        currentMenu: {}
    },
    getters: {
        user: state => state.user,
        menus: state => state.menus,
        currentMenu: state => state.currentMenu,
        userToken: state => state.userToken ?? localStorage.getItem('userToken'),
        wallet: state => state.wallet
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user
        },
        SET_MENUS(state, menus) {
            state.menus = menus
        },
        SET_CURRENT_MENU(state, menu) {
            state.currentMenu = menu
        },
        SET_USER_TOKEN(state, token) {
            state.userToken = token
            localStorage.setItem('userToken', token)
        },
        CLEAR_USER_DATA(state) {
            state.userToken = null
            state.user = {}
            localStorage.removeItem('userToken')
        },
        SET_WALLET(state, wallet) {
            state.wallet = wallet
        }
    },
    actions: {
        setUser({ commit }, payload) {
            commit('SET_USER', payload)
        },
        setMenus({ commit }, payload) {
            commit('SET_MENUS', payload)
        },
        setCurrentMenu({ commit }, payload) {
            commit('SET_CURRENT_MENU', payload)
        },
        setUserToken({ commit }, payload) {
            commit('SET_USER_TOKEN', payload)
        },
        clearUserData({ commit }) {
            commit('CLEAR_USER_DATA')
        },
        setWallet({ commit }, payload) {
            commit('SET_WALLET', payload)
        }
    }
})

export default store;