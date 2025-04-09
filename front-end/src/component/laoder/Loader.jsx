import React from 'react';
import {ClipLoader, HashLoader
} from "react-spinners";
import './Loader.css'

const Loader = () => {
    return (
        <div className='w-100 min-vh-100 d-flex justify-content-center align-items-center'>
            <HashLoader
                color="#348E38" loading={true} size={40} speedMultiplier={0.7} />
        </div>
    );
};

export default Loader;