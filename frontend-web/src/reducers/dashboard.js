const initiaState = {
    registry: [],
    users: [],
    contracts: [],
    invoices: [],
    payments: []
}


export default function dashboard(state = initiaState, action) {

    switch (action.type) {
    
        case "UPDATE_USERS":{
            return {
                ...state,
                users: action.payload
            }         
        }
        case "UPDATE_REGISTRY":{
            return {
                ...state,
                registry: action.payload
            }         
        }
        case "UPDATE_CONTRACTS":{
            return {
                ...state,
                contracts: action.payload
            }         
        }
        case "UPDATE_INVOICES":{
            return {
                ...state,
                invoices: action.payload
            }         
        }
        case "UPDATE_PAYMENTS":{
            return {
                ...state,
                payments: action.payload
            }         
        }


        default: {
            return state
        }
        
    }

}