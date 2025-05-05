const APP_CONFIG = {
    NAME: 'Kishalay School',
    API_TOKEN: 'kishalay_school',
    VERSION: '1.0.0',
    AUTHOR: 'DSI-Schools',
    DESCRIPTION: 'DSI-Schools',
    ICON: 'favicon.ico',
    API: 'https://portal.kishalayschool.in/v1/api/index.php',
}


const monthNames = {
    "January": 1,
    "February": 2,
    "March": 3,
    "April": 4,
    "May": 5,
    "June": 6,
    "July": 7,
    "August": 8,
    "September": 9,
    "October": 10,
    "November": 11,
    "December": 12
};


function calculateMonthCount(startMonth, endMonth) {
    const start = monthNames[startMonth];
    const end = monthNames[endMonth]; 
    let diff = end - start + 1; 
    if (diff <= 0) {
        diff += 12; 
    }

    return diff;
}
const validatePhone = (phone) => {
    if (phone.length > 10) {
        alert("Please enter a valid phone number")
        return false;
    }
    return true;
}

const calculateAge = (birthDate) => {
    const birth = new Date(birthDate);
    const currentYear = new Date().getFullYear();
    const janFirst = new Date(currentYear, 0, 1);
    let years = currentYear - birth.getFullYear();
    if (
        birth.getMonth() > janFirst.getMonth() ||
        (birth.getMonth() === janFirst.getMonth() && birth.getDate() > janFirst.getDate())
    ) {
        years -= 1;
    }
    const months = (janFirst.getMonth() - birth.getMonth() + 12) % 12;

    return `${years}Y ${months}M`;
};


const getCurrentDate = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
};

const getCurrentMonth = () => {
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    return month;
};

const getCurrentYear = () => {
    const today = new Date();
    const year = today.getFullYear();
    return year;
};

const getCurrentTime = () => {
    const today = new Date();
    const hours = String(today.getHours()).padStart(2, "0");
    const minutes = String(today.getMinutes()).padStart(2, "0");
    return `${hours}:${minutes}`;
};

const getCurrentSession = () => {
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const year = month == 11 ? today.getFullYear() + 1 : today.getFullYear();
    return `${year}-${year}`;
}

const getCurrentFinancialYear = () => {
    const today = new Date();
    // financial year declears from April to next year march 
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const year = month == 11 ? today.getFullYear() + 1 : today.getFullYear();
    return `${year - 1}-${year}`;
}

const getRecurringMonthsFromJan = ({ admissionDate }) => {
    const admissionDateObj = new Date(admissionDate);
    const admissionMonth = admissionDateObj.getMonth() + 1; // Get month (1-based)

    // If admission is in November, December, or January, return 1
    if (admissionMonth === 11 || admissionMonth === 12 || admissionMonth === 1) {
        return 1;
    }

    // Otherwise, return the month number (Feb = 2, Mar = 3, ..., Oct = 10)
    return admissionMonth;
}

function filterDataByConditions(data, conditions) {
    return data.filter((student) => {
        return Object.keys(conditions).every((key) => {
            if (key in student) {
                if (key === "due_amount" || key === "monthly_income") {
                    // Handle numeric comparisons
                    if (typeof conditions[key] === "object") {
                        const { min, max } = conditions[key];
                        const value = parseFloat(student[key]);
                        if (min !== undefined && value < min) return false;
                        if (max !== undefined && value > max) return false;
                    } else {
                        return student[key] == conditions[key];
                    }
                } else if (key === "date_of_birth" || key === "admission_date") {
                    // Handle date comparisons
                    const studentDate = new Date(student[key]);
                    const { from, to } = conditions[key];
                    if (from && studentDate < new Date(from)) return false;
                    if (to && studentDate > new Date(to)) return false;
                } else {
                    // Handle string or exact matches
                    return student[key].toString().toLowerCase() === conditions[key].toString().toLowerCase();
                }
            }
            return true; // Skip non-existing keys
        });
    });
}


export { APP_CONFIG, validatePhone, calculateAge, getCurrentDate, filterDataByConditions, getCurrentMonth, getCurrentYear, getCurrentTime, getCurrentSession, getCurrentFinancialYear, getRecurringMonthsFromJan, calculateMonthCount }