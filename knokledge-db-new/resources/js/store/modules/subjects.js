export default {
    state: {
        operations: {
            write: 'write',
            edit: 'edit',
            delete: 'delete',
            take: 'take'
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
        },
        getTakeOperation: state => {
            return state.operations.take;
        }
    },
    // actions: {},
    // mutations: {}
}
