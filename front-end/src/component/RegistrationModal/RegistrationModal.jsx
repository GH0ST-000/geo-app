import React from 'react';
import useModalStore from "../../store/registrationModalStore.js";

const RegistrationModal = () => {

    return (
        <div>
            <h2 className="text-xl font-bold mb-4">Register</h2>
            <form>

                <input type="text" placeholder="Username" className="border p-2 w-full mb-2"/>
                <input type="email" placeholder="Email" className="border p-2 w-full mb-2"/>
                <input type="password" placeholder="Password" className="border p-2 w-full mb-2"/>
                <button type="submit" className="bg-blue-500 text-white p-2 rounded w-full">Submit</button>
            </form>
            <button  className="mt-2 text-red-500">Close</button>
        </div>
    );
};

export default RegistrationModal;