/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import PrescriptionCardComponent from "./PrescriptionCard.vue";

describe("PrescriptionCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(PrescriptionCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
