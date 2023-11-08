/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import PatientDetailPage from "./PatientDetail.vue";

describe("PatientDetailPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(PatientDetailPage);
        expect(wrapper.exists()).toBe(true);
    });
});
