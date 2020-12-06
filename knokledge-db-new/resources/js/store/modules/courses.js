export default {
    state: {
        operations: {
            write: 'Write',
            edit: 'Edit',
            delete: 'Delete'
        }
    },
    getters: {
        getWriteOperation: state => {
            return state.operations.write;
        },
        getEditOperation: state => {
            return state.operations.edit;
        },
        getDeleteOperation: state => {
            return state.operations.delete;
        }
    },
    // actions: {},
    // mutations: {}
}
