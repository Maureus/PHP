export default {
    state: {
        operations: {
            write: 'write',
            edit: 'edit',
            delete: 'delete'
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
