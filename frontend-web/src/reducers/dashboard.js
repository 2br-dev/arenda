const initiaState = {
    registry: {}
}


export default function dashboard(state = initiaState, action) {

    switch (action.type) {

        case "SET_REGISTORY":{
            return {
                ...state,
                registry: action.payload
            }
        }
        
        default: {
            return state
        }
        
    }

}