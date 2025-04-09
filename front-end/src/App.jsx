import React, { useState, useEffect } from 'react';
import Layout from "./component/Layout/Layout.jsx";
import Router from "./routes/Router.jsx";
import { useTranslation } from "react-i18next";
import Loader from "./component/laoder/Loader.jsx";

const App = () => {
    const [showScroll, setShowScroll] = useState(false);
    const [loader, setLoader] = useState(true);
    const { t } = useTranslation();

    useEffect(() => {
        const timer = setTimeout(() => {
            setLoader(false);
        }, 3000);

        return () => clearTimeout(timer);
    }, []);

    useEffect(() => {
        const checkScroll = () => {
            if (window.pageYOffset > 200) {
                setShowScroll(true);
            } else {
                setShowScroll(false);
            }
        };

        window.addEventListener('scroll', checkScroll);
        return () => window.removeEventListener('scroll', checkScroll);
    }, []);

    const scrollToTop = () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };

    return (
        <Layout>
            {loader ? (
                <Loader />
            ) : (
                <>
                    <Router />
                    {showScroll && (
                        <button onClick={scrollToTop} className="top-scroll-btn">
                            <i className="fa-solid fa-angle-up"></i>
                        </button>
                    )}
                </>
            )}
        </Layout>
    );
};

export default App;
