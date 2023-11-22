/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import PrescriptionDetailPage from "./PrescriptionDetail.vue";

describe("PrescriptionDetailPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(PrescriptionDetailPage);
        expect(wrapper.exists()).toBe(true);
    });
});
