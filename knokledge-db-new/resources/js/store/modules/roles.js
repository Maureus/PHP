export default {
    state: {
        admin: 'admin',
        teacher: 'teacher',
        student: 'student'
    },
    getters: {
        getAdminRole: state => {
            return state.admin;
        },
        getStudentRole: state => {
            return state.student;
        },
        getTeacherRole: state => {
            return state.teacher;
        }
    },
    // actions: {},
    // mutations: {}
}
