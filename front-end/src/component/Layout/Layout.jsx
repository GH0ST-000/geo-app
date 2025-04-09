import React from 'react';
import Header from "../Header/Header.jsx";
import Footer from "../Footer/Footer.jsx";
import useModalStore from "../../store/registrationModalStore.js";
import RegistrationModal from "../RegistrationModal/RegistrationModal.jsx";

const Layout = ({children}) => {
    const { isModalOpen } = useModalStore();

    return (
        <div className='min-vh-100'>
            <Header />
            <main>
                {children}
            </main>
            <Footer />

            {isModalOpen && (
                <RegistrationModal />
            )}
        </div>

    );
};

export default Layout;